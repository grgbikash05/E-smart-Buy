@extends('layouts.app')

@section('content')

    <div class="container">
        <form action="/search" method="GET">
          {{ csrf_field() }}
          <div class="input-group mb-2">
              <input value="<?php echo isset($_GET['product']) ? $_GET['product'] : ''; ?>" type="text" id="product" class="form-control{{ $errors->has('product') ? ' is-invalid' : '' }}" name="product" placeholder="Search your item" aria-label="Search your products..." aria-describedby="button-addon2">
              <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fas fa-search text-grey"
                  aria-hidden="true"></i></button>
              </div>
          </div>
          @if ($errors->has('product'))
            <span class="invalid-feedback d-block mb-3 mt-0" role="alert">
                <strong>{{ $errors->first('product') }}</strong>
            </span>
          @endif
        </form>
    </div>


<div class="container-fluid">
    @if(!empty($results))
    <?php foreach($results as $result): ?>

        <div class="row productBox">
        <div class="col-sm-2 box">
                <div class="inner"><img src="<?php echo $result->link; ?>" alt=""></div>
        </div>
        <div class="col-sm-7 box">
                <div class="inner">
                    <div class="row centerMainRow">
                        <p><?php echo $result->title; ?></p>
                    </div>
                    <div class="row">
                        <div class="col-sm-3"><?php 
                            if($result->site == "daraz") {
                                echo "<img src='https://laz-img-cdn.alicdn.com/images/ims-web/TB1eIwbmljTBKNjSZFuXXb0HFXa.png'>";
                            } else if($result->site == "muncha") {
                                echo "<img src='https://muncha.com/assets/images/logo.gif'>";
                            }
                        ?></div>
                    </div>
                    <div class="row centerMainRow">
                        <img src="<?php echo $result->image ? $result->image : 'http://127.0.0.1:8000/images/no_image.jpg'; ?>" alt="#">
                    </div>
                </div>
        </div>
            <div class="col-sm-3 box">
                    <div class="inner">
                        <div class="row centerMainRow">
                            <p>Rs. <?php echo $result->price; ?></p>
                        </div>
                        <div class="row">
                            <a target="_blank" href="<?php echo $result->link; ?>" class="btn btn-primary">View Deal</a>
                        </div>
                    </div> 
            </div>
        </div>

    <?php endforeach; ?>  
    @else

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="text-danger">Something went wrong. Please try again.</div>
            </div>
        </div>
    </div>

    @endif
</div>

@endsection