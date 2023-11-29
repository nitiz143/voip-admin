<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="noindex,nofollow" />
        <style>           
            body { background-color: #F9F9F9; color: #222; font: 14px/1.4 Helvetica, Arial, sans-serif; margin: 0; padding-bottom: 45px; }
            .exception{ margin: 10px !important;}
            .exception-summary { background: #f2443e; border-bottom: 2px solid rgba(0, 0, 0, 0.1); border-top: 1px solid rgba(0, 0, 0, .3); flex: 0 0 auto; margin-bottom: 30px; }
            .exception-message-wrapper { display: flex; align-items: center; min-height: 70px; }
            .exception-message { flex-grow: 1; padding: 30px 0;  margin: 15px; }
            .exception-message, .exception-message a { color: #FFF; font-size: 21px; font-weight: 400; margin: 15px; }
            .exception-message.long { font-size: 18px; }
            .exception-message a { border-bottom: 1px solid rgba(255, 255, 255, 0.5); font-size: inherit; text-decoration: none; }
            .exception-message a:hover { border-bottom-color: #ffffff; }
            .exception-illustration { flex-basis: 111px; flex-shrink: 0; height: 66px; margin-left: 15px; opacity: .7; }
        </style>
    </head>
    <body>
        <div class="exception-summary">
            <div class="container">
                <div class="exception-message-wrapper">
                    <h1 class="break-long-words exception-message">Update CMS by {{Auth::user()->name}}.</h1>
                    <h3 class="exception"> Role:- {{Auth::user()->role}}.</h3>&nbsp;&nbsp;&nbsp;&nbsp;
                    <h3 class="exception"> Email:- {{Auth::user()->email}}</h3>
                </div>
            </div>
        </div>

        <div class="container" style="display: flex; justify-content: space-around;">
            <div class="form-control">
                <h2>Updated values in Cms</h2>
                
                @if(!empty($updated_values))
                    @foreach( $updated_values as $key => $updated_value )
                        <h3>{{$key}}:-  &nbsp;&nbsp;<span style="font-size: 14px; font-weight: 100;">{{$updated_value}}</span></h3>
                    @endforeach
                @else
                    <hr>
                    <h3> No Changes</h3>
                @endif
            </div>
            <div class="form-control">
                <h2>Old values in Cms</h2>
                @if(!empty($old_values))
                    @foreach( $old_values as $key => $old_value )
                
                        <h3>{{$key}}:-  &nbsp;&nbsp;<span style="font-size: 14px; font-weight: 100;">{{$old_value}}</span></h3>
                    @endforeach
                @else
                    <hr>
                    <h3> No Changes</h3>
                @endif
            </div>
        </div>
    </body>
</html>
