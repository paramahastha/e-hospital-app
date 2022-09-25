<!-- resources/views/consult/chat.blade.php -->
@extends('layouts.app')
@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6 mt-3">
                   <div class="d-flex align-items-center">
                   {{ __('Consultation') }}
                   </div>
                </div>                
             </div>                        
        </div>
        <div class="card-body">
            <chat-messages v-on:fetchmessage="fetchmessage"  :messages="messages" :consult="{{ $consult }}"></chat-messages>
        </div>
        <div class="card-footer">
            <chat-form v-on:addmessage="addmessage" :messages="messages" :user="{{ Auth::user() }}" :consult="{{ $consult }}"></chat-form>
        </div>
    </div>
</div>
@endsection