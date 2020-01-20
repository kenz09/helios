<?php

namespace App\Http\Controllers;

use App\Project;
use App\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    //

    /**
     * Paginate the authenticated user's tasks.
     *
     * @return \Illuminate\View\View
     */
    public function index(Project $project)
    {
        // paginate the authorized user's tasks with 5 per page
        $tasks = $project
            ->tasks()
            ->orderBy('is_complete')
            ->orderByDesc('created_at')
            ->paginate(5);

        // return task index view with paginated tasks
        return view('tasks', [
            'tasks' => $tasks,
            'project'=> $project
        ]);
    }

    /**
     *
     * Returns form to create new task
     *
     * @return \Illuminate\View\View
     */

    public function create(Project $project){
        return view('newTaskForm',[
                'project'=>$project
            ]);
    }

    /**
     * Store a new incomplete task for the authenticated user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, Project $project)
    {
        // validate the given request
        $data = $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'user' => 'required'
        ]);

        // create a new incomplete task with the given title
        Auth::user()->tasks()->create([
            'title' => $data['title'],
            'description' => $data['description'],
            'is_complete' => false,
            'is_approved' => false,
            'project_id' => $project->id,
            'user_id' => $data['user'],
        ]);

        // flash a success message to the session
        session()->flash('status', 'Task Created!');

        // redirect to project index
        return redirect('/project/'.$project->id);
    }

    /**
     * Mark the given task as complete and redirect to tasks index.
     *
     * @param \App\Task $task
     * @return \Illuminate\Routing\Redirector
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Project $project, Task $task)
    {
        // check if the authenticated user can complete the task
        $this->authorize('complete', $task);

        // mark the task as complete and save it
        $task->is_complete = true;
        $task->save();

        // flash a success message to the session
        session()->flash('status', 'Task Completed!');

        // redirect to tasks index
        return redirect('/project/'.$project->id);
    }

}
