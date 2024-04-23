@extends('shopify-app::layouts.default')

@section('content')

    <script type="text/javascript">
        const userString = '<?php echo Auth::user(); ?>';
        const hostName = "<?php echo app('request')->input('host');?>";
        
        window.auth = JSON.parse(userString);
        window.host_name = hostName; //host name used in activate plan

    </script>
    <div id="app" data-shop="{{ Auth::user()->name }}"></div>    

@endsection

@section('scripts')
    @parent
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        actions.TitleBar.create(app, { title: 'Demo App' });
    </script>
@endsection
