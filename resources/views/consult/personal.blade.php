@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row justify-content-center">
      <div class="col-md-10">
         <div class="card">
            <div class="card-header">
               <div class="row">
                  <div class="col-md-6 mt-3">
                     <div class="d-flex align-items-center">
                     {{ __('Lengkapi Data Personal') }}
                     </div>
                  </div>
                  <div class="col-md-6 mt-2 mb-2">
                     <a href="{{ URL::previous() }}" class="btn btn-secondary float-end">
                        Kembali
                     </a>
                  </div>  
               </div>             
            </div>

            <div class="card-body">
                  @if (session('status'))
                     <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                     </div>
                  @endif
                  <br>                                                             
            </div>
         </div>
      </div>
   </div> 
</div>

@endsection