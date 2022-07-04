<script src="{{ asset('assets/js/tinymce/tinymce.js') }}"></script>
<script>
    $(window).on('load', function (){
        var editor_config = {
            selector: '.tinymce',
            height : "300",
            language: "en",
            base_url: '/assets/js/tinymce/',
            theme_url: "{{asset('assets/js/tinymce/themes/silver/theme.min.js')}}",
            icons_css: "{{asset('assets/js/tinymce/icons/default/icons.min.css')}}",
            content_css: "{{asset('assets/js/tinymce/skins/content/default/content.css')}}",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent",
            relative_urls: true,
        };


        tinymce.init(editor_config);
    });

</script>
