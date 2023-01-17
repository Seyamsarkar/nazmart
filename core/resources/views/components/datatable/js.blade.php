<!-- Start datatable js -->
<script src="{{global_asset('assets/common/js/jquery.dataTables.js')}}"></script>
<script src="{{global_asset('assets/common/js/jquery.dataTables.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/dataTables.responsive.min.js')}}"></script>
<script src="{{global_asset('assets/common/js/responsive.bootstrap.min.js')}}"></script>
<script>
    (function($){
        "use strict";
        $(document).ready(function() {
            $('.table-wrap > table').DataTable( {
                "order": [[ 1, "desc" ]],
                'columnDefs' : [{
                    'targets' : 'no-sort',
                    "orderable" : false
                }]
            } );
        } );

    })(jQuery)
</script>
