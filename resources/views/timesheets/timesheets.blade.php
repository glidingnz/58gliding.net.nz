@extends('layouts.app')

@section('page-scripts')
<link href="{{ asset('/css/timesheets.css')}}" rel='stylesheet' />
@endsection

@section('content')


<div class="container-fluid" id="timesheets">
    <div class="flights-grid">
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
             <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
        <div class="card flight">
            <div class="card-body card-grid">
                <!--ROW 1-->
                <p class='card-text bold left' style="grid-area:1/1/1/2">GPJ</p>
                <p class='card-text' style="grid-area:1/2/1/5">15:58</p>
                <p class='card-text' style="grid-area:1/5/1/8">__:__</p>
                <p class='card-text' style="grid-area:1/8/1/11">10h36m</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:1/11/1/12;margin:0 auto">></button>
                <!--ROW 2-->
                <p class='card-text bold left' style="grid-area:2/1/2/1">PIC:&nbsp;</p>
                <p class='card-text long' style="grid-area:2/2/2/12">Pilot With A Very Long Name</p>
                <!--ROW 3-->
                <p class='card-text bold left' style="grid-area:3/1/3/1">P2:&nbsp;</p>
                <p class='card-text long' style="grid-area:3/2/3/12">Another Pilot With A Very Long Name</p>
                <!--ROW 4-->
                <p class='card-text bold left' style="grid-area:4/1/4/1">$:&nbsp;&nbsp;</p>
                <p class='card-text long' style="grid-area:4/2/4/11">Very Long Payment Mode Can't Fit Into The Card</p>
                <button type="button" class="btn btn-primary btn-sm btn-block" style="width:2em;height:2em;grid-area:4/11/4/12;margin:0 auto">.</button>
            </div>
        </div>
    </div>
</div>

@endsection
