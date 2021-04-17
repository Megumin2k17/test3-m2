<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="description" content="Chartist.html">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/front/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/front/css/app.bundle.css">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/front/css/skins/skin-master.css">
    <link rel="stylesheet" media="screen, print" href="/front/css/fa-solid.css">
    <link rel="stylesheet" media="screen, print" href="/front/css/fa-brands.css">
    <link rel="stylesheet" media="screen, print" href="/front/css/fa-regular.css">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous"> -->
</head>
    <body class="mod-bg-1 mod-nav-link">
       
		<?php $this->insert('particals/navbar'); ?>
        <?=$this->section('content'); ?>
        <?php $this->insert('particals/footer'); ?>
       
       
        
    </body>



    <script src="/front/js/vendors.bundle.js"></script>
    <script src="/front/js/app.bundle.js"></script>
    <script>

        $(document).ready(function()
        {

            $('input[type=radio][name=contactview]').change(function()
                {
                    if (this.value == 'grid')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-g');
                        $('#js-contacts .col-xl-12').removeClassPrefix('col-xl-').addClass('col-xl-4');
                        $('#js-contacts .js-expand-btn').addClass('d-none');
                        $('#js-contacts .card-body + .card-body').addClass('show');

                    }
                    else if (this.value == 'table')
                    {
                        $('#js-contacts .card').removeClassPrefix('mb-').addClass('mb-1');
                        $('#js-contacts .col-xl-4').removeClassPrefix('col-xl-').addClass('col-xl-12');
                        $('#js-contacts .js-expand-btn').removeClass('d-none');
                        $('#js-contacts .card-body + .card-body').removeClass('show');
                    }

                });

                //initialize filter
                initApp.listFilter($('#js-contacts'), $('#js-filter-contacts'));
        });

    </script>
</html>