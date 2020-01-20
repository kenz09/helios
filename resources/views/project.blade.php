@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$project->title}}</div>

                <div class="card-body">
                   {{$project->description}}
                </div>
                <div class="card-footer">
                    <form method="GET" action="{{ '/project/'.$project->id.'/members' }}">
                        <button type="submit" class="btn btn-primary">Show Members</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chats</div>

                <div class="panel-body">
                    <chat-messages :messages="messages"></chat-messages>
                </div>
                <div class="panel-footer">
                    <chat-form
                        v-on:messagesent="addMessage"
                        :user="{{ Auth::user() }}"
                        :project="{{ $project }}"
                    ></chat-form>
                </div>
            </div>
        </div>
    </div> --}}
    <br>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tasks</div>

                <div class="card-body">
                   <table class="table table-striped">
                       @foreach ($project->tasks as $task)
                           <tr>
                               <td>
                                   @if ($task->is_complete)
                                       <s>{{ $task->title }}</s>
                                   @else
                                       {{ $task->title }}
                                   @endif
                               </td>
                               <td class="text-right">
                                   @if (! $task->is_complete)
                                       <form method="POST" action="{{ '/project/'.$project->id.'/tasks/update/'.$task->id.'/' }}">
                                           @csrf
                                           @method('PATCH')
                                           <button type="submit" class="btn btn-primary">Complete</button>
                                       </form>
                                   @endif
                               </td>
                           </tr>
                       @endforeach
                   </table>

                </div>

                <div class="card-footer">
                    <form method="GET" action="{{ '/project/'.$project->id.'/tasks' }}">
                        <button type="submit" class="btn btn-primary">+ | Create new task</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
