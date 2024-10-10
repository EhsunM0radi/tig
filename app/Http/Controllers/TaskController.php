<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = Task::where('project_id', $project->id)->with('assignee')->get();
        return Inertia::render('Tasks/Index', [
            'project' => $project,
            'tasks' => $tasks,
        ]);
    }

    public function create(Project $project)
    {
        return Inertia::render('Tasks/Create', [
            'project' => $project
        ]);
    }

    public function store(StoreTaskRequest $request, Project $project)
    {
        $validated = $request->validated();

        $project->tasks()->create($validated);

        return redirect()->route('projects.tasks.index', $project->id)->with('success', 'Task created successfully.');
    }

    public function show(Project $project, Task $task)
    {
        return Inertia::render('Tasks/Show', [
            'project' => $project,
            'task' => $task
        ]);
    }

    public function edit(Project $project, Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'project' => $project,
            'task' => $task
        ]);
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $validated = $request->validated();

        $task->update($validated);

        return redirect()->route('projects.tasks.index', $project->id)->with('success', 'Task updated successfully.');
    }

    public function destroy(Project $project, Task $task)
    {
        $task->delete();
        return redirect()->route('projects.tasks.index', $project->id)->with('success', 'Task deleted successfully.');
    }
}
