<script>
    (function($){
        "use strict";
        $(document).ready(function(){
            $(document).on('click','.language_dropdown ul li',function(e){
                var el = $(this);
                el.parent().parent().find('.selected-language').text(el.text());
                el.parent().removeClass('show');
                $.ajax({
                    url : "{{route('tenant.frontend.langchange')}}",
                    type: "GET",
                    data:{
                        'lang' : el.data('value')
                    },
                    success:function (data) {

                        location.reload();
                    }
                })
            });
        });
    }(jQuery));
</script>
