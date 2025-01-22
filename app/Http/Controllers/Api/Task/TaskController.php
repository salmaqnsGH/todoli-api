<?php

namespace App\Http\Controllers\Api\Task;

use App\Constants\ImageStorageFolder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\GetPaginatedListRequest;
use App\Http\Requests\Api\Task\AssignTaskUserRequest;
use App\Http\Requests\Api\Task\CreateTaskRequest;
use App\Http\Requests\Api\Task\SetTaskPriorityRequest;
use App\Http\Requests\Api\Task\SetTaskStatusRequest;
use App\Http\Requests\Api\Task\UpdateTaskRequest;
use App\Http\Requests\AppRequest;
use App\Services\Api\ImageService;
use App\Services\Api\Task\TaskService;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService,
        protected ImageService $imageService,
    ) {}

    public function getPaginatedList(GetPaginatedListRequest $request)
    {
        $result = $this->taskService->getPaginatedList($request);

        return jsonresSuccess($request, 'Success get list data', $result);
    }

    public function getDetail(AppRequest $request)
    {
        $result = $this->taskService->getDetail($request);

        return jsonresSuccess($request, 'Success get data', $result);
    }

    public function create(CreateTaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $task = $this->taskService->create($request);

            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $this->imageService->store(
                        $image,
                        ImageStorageFolder::TASK,
                        $task->id
                    );

                    // Create TaskImage record
                    $task->images()->create([
                        'image' => $imagePath,
                    ]);
                }
            }

            DB::commit();

            return jsonresCreated($request, 'Success create data', $task);
        } catch (\Exception $e) {
            if (isset($task)) {
                foreach ($task->images as $taskImage) {
                    $this->imageService->delete($taskImage->image);
                }
            }

            DB::rollback();
            throw $e;
        }
    }

    public function update(UpdateTaskRequest $request)
    {
        try {
            DB::beginTransaction();

            $task = $this->taskService->update($request);

            if ($request->hasFile('images')) {
                // Store old images for cleanup
                $oldImages = $task->images()->get();

                // Delete old image records
                $task->images()->delete();

                // Upload and create new images
                foreach ($request->file('images') as $image) {
                    $imagePath = $this->imageService->store(
                        $image,
                        ImageStorageFolder::TASK,
                        $task->id
                    );

                    $task->images()->create([
                        'image' => $imagePath,
                    ]);
                }
            }

            DB::commit();

            return jsonresSuccess($request, 'Success update data', $task);
        } catch (\Exception $e) {
            if (isset($task)) {
                foreach ($task->images as $taskImage) {
                    $this->imageService->delete($taskImage->image);
                }
            }

            DB::rollback();
            throw $e;
        }
    }

    public function softDelete(AppRequest $request)
    {
        $result = $this->taskService->softDelete($request);

        return jsonresSuccess($request, 'Data is deleted', $result);
    }

    public function assignUser(AssignTaskUserRequest $request)
    {
        $result = $this->taskService->assignUser($request);

        return jsonresSuccess($request, 'Task user is assigned', $result);
    }

    public function setStatus(SetTaskStatusRequest $request)
    {
        $result = $this->taskService->setStatus($request);

        return jsonresSuccess($request, 'Task status is set', $result);
    }

    public function setPriority(SetTaskPriorityRequest $request)
    {
        $result = $this->taskService->setPriority($request);

        return jsonresSuccess($request, 'Task priority is set', $result);
    }
}
