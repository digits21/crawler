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
            <!-- FORM DIV START -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-right">
                    @if(session()->has('error_msg'))
                        <div class="alert alert-danger">
                            {{session('error_msg')}}
                        </div>

                    @endif
                    <form class="form" id="crawl_form" method="GET" action="{{url('crawl-url')}}">
                        <!-- URL start-->
                        @csrf
                          <div class="mb-3 form-group">
                            <label for="url" class="form-label">URL</label>
                            <input type="url" class="form-control" id="url" name="url" value="{{old('url')}}" placeholder="https://example.com" required>
                            <small id="url_alert" style="color:red;"></small>
                          </div>
                        <!-- URL end -->

                        <!-- submit button -->
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg float-right" onclick="submitForm()">Submit
                                </button>
                            </div>
                            
                    </form>
                </div>

            </div>
            <!-- FORM DIV END-->
        @if(isset($crawl_results))
            <!-- RESULT TABLE START-->
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 text-right">
                    <table class="table">
                        <thead>
                            <th>CREATION DATE</th>
                            <th>TITLE</th>
                            <th>DESCRIPTION</th>
                            <th>SCREENSHOT</th>
                            <th>VIEW DETAIL</th>
                        </thead>
                        <tbody>
                            @foreach($crawl_results as $result)
                                <tr>
                                    <td>
                                        {{$result->created_at}}
                                    </td>
                                    <td>
                                        <a href="{{$result->url}}">{{($result->title!=NULL)?$result->title:''}}
                                        </a>
                                    </td>
                                    <td>
                                        {{($result->description!="NULL")?$result->description:''}}
                                    </td>
                                    <td>
                                        <a href="{{asset($result->screenshot)}}" target="_blank">{{$result->screenshot}}</a>
                                    </td>
                                    <td>
                                        <a href="{{url('view-crawl/'.$result->id)}}">
                                            view detail
                                        </a>
                                    </td>
                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
            <!-- RESULT TABLE END-->
        @endif


        </div>
    </body>

    <script type="text/javascript">
        function isValideUrl(url) {

             var regex = /^https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)$/;
              if(!regex .test(url)) {
                
                return false;
              } else {
                return true;
              }
            
        }
        function submitForm() {
            
                let validator=true
                let form_url=$("#url").val()
                if (form_url=="" || form_url=="undifined") {
                    $("#url_alert").html("url cannot be empty");
                    $("#url").focus()
                    validator=false
                }
                if (!isValideUrl(form_url)) {
                    $("#url_alert").html("invalide url");
                    $("#url").focus()
                    validator=false
                }
                // check if url is reachable


                if (validator) {
                    $("#crawl_form").submit()
                    $("#loading").css("display","block")
                }
        }
        window.onload=function(){
            $("#loading").css("display","none");
        }
       
    </script>
</html>
