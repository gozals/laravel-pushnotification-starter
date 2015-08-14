@extends('app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>

                    <div class="panel-body">
                        <div class="block-content">
                            <h2><strong>Notification</strong>  Message</h2>
                            <form method="post" action="" autocomplete="off">
                                <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />

                                <div  class="form-group">
                                    <label>Send To:</label>
                                    <select id="send_to"   name="send_to" class="form-control">
                                        @foreach($data as $data1)
                                            <option value="{{$data1['id']}}">{{$data1['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Title:</label>
                                    <input type="text"  name="title" class="form-control" >
                                </div>

                                <div class="form-group">
                                    <label>Text:</label>
                                    <textarea  name="send_text" class="form-control"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg"><i class="fa fa-cloud-upload"></i> Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
