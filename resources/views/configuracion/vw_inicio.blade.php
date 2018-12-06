@extends('layouts.admin')

@section('content')
<style>
    .smart-form fieldset {    
        padding: 5px 8px 0px;   
    }
    .smart-form section {
        margin-bottom: 5px;    
    }
    .smart-form .label {  
        margin-bottom: 0px;   
    }
    .smart-form .col {
        padding-right: 8px;
        padding-left: 8px;       
    }
</style>
<section id="widget-grid" class="">
    <div class="row padding-top-15">
        <div class="col-md-12 col-lg-12 hidden-xs">
            <div id="myCarousel" class="carousel fade" style="margin-bottom: 20px;">
                <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1" class=""></li>
                </ol>
                <div class="carousel-inner">
                        <!-- Slide 1 -->
                        <div class="item active" style="width:1500px; max-height:750px">
                                <img src="{{asset('img/img_cromotex/fondointranet-01.jpg')}}" alt="FLOTA CROMOTEX" class="img-fluid">
                                <div class="carousel-caption caption-right">
                                    <h1><b>VIAJES CONFIABLES AL MEJOR PRECIO</b></h1>
                                        <p>
                                                TRANSPORTES CROMOTEX
                                        </p>
                                        <br>
                                        <a target="_blank" href="https://www.cromotex.com.pe/" class="btn btn-danger btn-sm">VER MAS INFORMACION</a>
                                </div>
                        </div>
                        <!-- Slide 2 -->
                        <div class="item" style="width:1500px; max-height:750px">
                                <img src="{{asset('img/img_cromotex/fondointranet-02.jpg')}}" alt="FOTO PANORAMICA DE NUESTRAS INSTALACIONES" class="img-fluid">
                                <div class="carousel-caption caption-left">
                                    <h1><b>NOS RENOVAMOS PARA OFRECERTE UN MEJOR SERVICIO</b></h1>
                                        <p>
                                            TRANSPORTES CROMOTEX
                                        </p>
                                        <br>
                                        <a target="_blank" href="https://www.cromotex.com.pe/" class="btn btn-danger btn-sm">VER MAS INFORMACION</a>
                                </div>
                        </div>
                        
                </div>
                <a class="left carousel-control" href="#myCarousel" data-slide="prev"> <span class="glyphicon glyphicon-chevron-left"></span> </a>
                <a class="right carousel-control" href="#myCarousel" data-slide="next"> <span class="glyphicon glyphicon-chevron-right"></span> </a>
        </div>
                
        </div>
    </div>
</section>

@section('page-js-script')
<script type="text/javascript">
    $(document).ready(function (){
        
        $("#menu_configuracion").show();
        $("#li_config_inicio").addClass('cr-active');
        
        $('.carousel.fade').carousel({
                interval : 3000,
                cycle : true
        });
        
    });
</script>

@stop

@endsection