@extends('layouts.adminLayout')

@section('title', 'Second Repostation List')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Hisobotlar</h1>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered text-center table-striped">
                            <thead class="thead-dark">
                            <tr>
                                <th>№</th>
                                <th>Areas/
                                    Category
                                </th>
                                <th>Status</th>
                                @foreach($areas as $hudud)
                                    <th style="writing-mode: vertical-rl; transform: rotate(180deg);">
                                        {{$hudud->name}}
                                    </th>
                                @endforeach
                                <th>Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <th>{{$category->id}}</th>
                                    <td>{{$category->name}}</td>
                                    <td>
                                        <table class="table table-borderless">
                                            <tr><td>Approved</td></tr>
                                            <tr><td>Done</td></tr>
                                            <tr><td>Opened</td></tr>
                                            <tr><td>Sent</td></tr>
                                            <tr><td>Rejected</td></tr>
                                        </table>
                                    </td>
                                    @foreach($areas as $hudud)
                                        <td>
                                            <table class="table table-borderless">
                                                @foreach(['approved' => 'success', 'done' => 'primary', 'opened' => 'warning', 'sent' => 'info'] as $status => $color)                            
                                                    <tr>
                                                        <td>
                                                            <button class="btn btn-{{$color}} btn-sm">
                                                                {{$category->taskAreas->where('area_id', $hudud->id)->where('status', $status)->count()}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td>
                                                        <button class="btn btn-danger btn-sm">
                                                            {{$category->taskAreas->where('area_id', $hudud->id)->where('period', '<', date('Y-m-d'))->where('status','!=','approved')->count()}}
                                                           </button>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    @endforeach
                                    <td>
                                        <table class="table table-borderless">
                                            @foreach(['approved' => 'success', 'done' => 'primary', 'opened' => 'warning', 'sent' => 'info'] as $status => $color)
                                                <tr>
                                                    <td>
                                                        <button class="btn btn-{{$color}} btn-sm">
                                                            {{$category->taskAreas->where('status', $status)->count()}}
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>
                                                    <button class="btn btn-danger btn-sm">
                                                        {{$category->taskAreas->where('period', '<', now())->where('status','!=','approved')->count()}}
                                                    </button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
