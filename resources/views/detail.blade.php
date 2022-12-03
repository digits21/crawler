<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" type="text/css" href="{{asset('css/bootstrap.min.css')}}">
        <style type="text/css">
            #loading{
                position: absolute;
                z-index: 1000;
                margin-top: 5%;
                margin-left:45%;
                display:block;
            }
        </style>
        <script  src="{{asset('js/jquery-3.6.1.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
    </head>
    <body >
        <img src="{{asset('img/loading.gif')}}" id="loading">
        <div class="container" style="margin-top:10%;">
            @if(isset($crawl))
                <!-- Screenshot -->
                <div class="row justify-content-center">
                   
                    <div class="col-12 col-md-8 text-right">
                         <a href="{{url()->previous()}}">
                        BACK
                    </a>
                       <h1>SCREENSHOT</h1>
                       <small>
                           (CREATED AT: {{$crawl->created_at}})
                       </small>
                       @if($crawl->screenshot!="")
                            <img src="{{asset($crawl->screenshot)}}" width="100%">
                        @else
                        <br>
                        <span>
                            NO SCREENSHOT
                        </span>
                            
                        @endif
                    </div>

                </div>
                <hr>
                <!-- TITLE -->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 text-right">
                       <h1>TITLE</h1>
                        <a href="{{$crawl->url}}">{{($crawl->title!=NULL)?$crawl->title:''}}</a>
                    </div>

                </div>
                <hr>
                 <!-- DESCRIPTION-->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 text-right">
                       <h1>DESCRIPTION</h1>
                        {{($crawl->description!="NULL")?$crawl->description:'NO DESCRIPTION'}}
                    </div>

                </div>
                <hr>
                 <!-- BODY-->
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 text-right">
                       <h1>BODY</h1>
                        {!!($crawl->body!=NULL)?$crawl->body:''!!}
                    </div>

                </div>

            @endif
            
        
            


        </div>
    </body>
    <script type="text/javascript">
         window.onload=function(){
            $("#loading").css("display","none");
        }
    </script>
    
</html>
