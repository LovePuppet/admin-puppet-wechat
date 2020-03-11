<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <b>Version</b> {{ env('VERSION') }}
    </div>  
    <strong>Copyright &copy; {{ date('Y')}} <a href="{{ env('COMPANY_URL') }}" target="_blank">{{ env('COMPANY_NAME') }}</a>.</strong> All rights reserved.
</footer>