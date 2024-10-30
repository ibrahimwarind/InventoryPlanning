@extends('frontend.master')

@section('content')
<?php $con=mysqli_connect("127.0.0.1","appcelebricare_ibrahim","00324ahmed@","appcelebricare_DQuotes"); 
function getUserIpAddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP from shared internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP passed from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // IP address from remote address
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
session_start();
$userIp = getUserIpAddr();
$clientid=intval(session("user_id"));
 $ins_impression="INSERT INTO `provider_profile_click`(datetime,ip_address,provider_id,client_id) VALUES (NOW(),'$userIp','$doctorid','$clientid')";
$run_impression=mysqli_query($con,$ins_impression);
?>
<!-- Breadcrumb -->
 @foreach($doctordata as $data)
            <div class="breadcrumb-bar">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-12 col-12">
                            <nav aria-label="breadcrumb" class="page-breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Search</li>
                                    <li class="breadcrumb-item active" aria-current="page">Provider Profile</li>
                                </ol>
                            </nav>
                            <h2 class="breadcrumb-title">{{$data->name}} ({{$data->designation}}) - {{$data->clinic_city}},{{$data->clinic_province}}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Breadcrumb -->
           
            <?php $image=$data->image; 
            
            $detaillink="https://app.celebricare.com/warind/provider_appointment/{{$data->id}}";
                          //get link
                          $fg_getlink="SELECT CONCAT('https://app.celebricare.com/warind/provider_appointment/', LOWER(IFNULL(dc.title, '')), '/', REGEXP_REPLACE(REPLACE(LOWER(d.name), ' ', '-'), '[^a-z0-9-]', ''), '-', LOWER(IFNULL(d.clinic_city, '')), '-', LOWER(IFNULL(d.clinic_province, '')), '-', LOWER(IFNULL(d.clinic_zip, '')), '-', LOWER(IFNULL(d.country_code, '')), '/', d.id) AS url FROM doctors d INNER JOIN doctor_cats dc ON d.category_id = dc.id WHERE d.id ='$data->id'";
                          $run_getlink=mysqli_query($con,$fg_getlink);
                          if(mysqli_num_rows($run_getlink) !=0)
                          {
                              $row_getlink=mysqli_fetch_array($run_getlink);
                              $detaillink=$row_getlink['url'];
                              
                          }
            
            ?>
            <!-- Page Content -->
            <div class="content">
                <div class="container">

                    <!-- Provider Widget -->
                 <div class="card">
                                <div class="card-body">
                                    <div class="Provider-widget">
                                        <div class="doc-info-left">
                                            <div class="Provider-img"  style='width:250px !important'>
                                                <a href="https://app.celebricare.com/warind/provider_detail/{{$data->id}}" style='height:200px !important;width:170px !important'>
                                                    @if(file_exists(storage_path('app/public/' . $data->image)) && !empty($data->image))
    <img src="{{ asset('storage/' . $data->image) }}" class="img-fluid" style='height:200px !important;width:170px !important' alt="{{ $data->name }}">
@else
    <img src="{{ asset('storage/uploads/newphoto.jpg') }}" class="img-fluid" style='height:200px !important;width:170px !important' alt="{{ $data->name }}">
@endif
                                                </a>
                                            </div>
                                            <div class="doc-info-cont" style='margin-left:50px'>
                                                <h4 class="doc-name"><a href="https://app.celebricare.com/warind/provider_detail/{{$data->id}}">{{$data->name}}</a></h4>
                                                <p class="doc-speciality">{{$data->designation}}</p>
                                                <p style='width:650px'><?php $vars=substr($data->about_youself, 0, 880); echo $vars; ?>..</p>
                                                <div class="rating">
                                                    <?php 
                                                    $count=1;
                                                    for($i=1;$i <= $data->rating;$i++)
                                                    {
                                                       echo "  <i class='fas fa-star filled'></i>"; 
                                                       $count=$count + 1;
                                                    }
                                                    for($i=$count;$i <=5 ; $i++)
                                                    {
                                                        echo '<i class="fas fa-star"></i>';
                                                    }
                                                    ?>
                                                  
                                                    
                                                    <span class="d-inline-block average-rating">({{$data->rating}})</span>
                                                </div>
                                                <!--<div class="clinic-details">-->
                                                <!--    <p class="doc-location"><i class="fas fa-map-marker-alt"></i> Florida, USA</p>-->
                                                <!--    <ul class="clinic-gallery">-->
                                                <!--        <li>-->
                                                <!--            <a href="assets/img/features/feature-01.jpg" data-fancybox="gallery">-->
                                                <!--                <img src="assets/img/features/feature-01.jpg" alt="Feature">-->
                                                <!--            </a>-->
                                                <!--        </li>-->
                                                <!--        <li>-->
                                                <!--            <a href="assets/img/features/feature-02.jpg" data-fancybox="gallery">-->
                                                <!--                <img  src="assets/img/features/feature-02.jpg" alt="Feature">-->
                                                <!--            </a>-->
                                                <!--        </li>-->
                                                <!--        <li>-->
                                                <!--            <a href="assets/img/features/feature-03.jpg" data-fancybox="gallery">-->
                                                <!--                <img src="assets/img/features/feature-03.jpg" alt="Feature">-->
                                                <!--            </a>-->
                                                <!--        </li>-->
                                                <!--        <li>-->
                                                <!--            <a href="assets/img/features/feature-04.jpg" data-fancybox="gallery">-->
                                                <!--                <img src="assets/img/features/feature-04.jpg" alt="Feature">-->
                                                <!--            </a>-->
                                                <!--        </li>-->
                                                <!--    </ul>-->
                                                <!--</div>-->
                                                <?php if($data->specialties !=""){ ?>
                                                <div class="clinic-services">
                                                    <?php $values = explode(", ", $data->specialties);
                                                    foreach($values as $val)
                                                    {
                                                        echo "  <span>$val</span>";
                                                    }
                                                    ?>
                                                  
                                                    
                                                </div>
                                                <?php } ?>
                                                <div class="clinic-services" style='margin-top:15px'>
                                                    <span><i class="far fa-thumbs-up"></i> 0</span>
                                                    <span><i class="far fa-comment"></i> 0 Feedback</span>
                                                    <span><i class="fas fa-map-marker-alt"></i> {{$data->clinic_city}}, USA</span>
                                                    <span><i class="far fa-money-bill-alt"></i> ${{$data->consultation_fee}} </span>
                                                    <span>{{$data->experience_year}} Yr Exp</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="doc-info-right" style='margin-top:-70px !important'>
                                            <div class="Provider-action">
                                        <a href="javascript:void(0)" class="btn btn-white fav-btn">
                                            <i class="far fa-bookmark"></i>
                                        </a>
                                        <a href="chat.html" class="btn btn-white msg-btn">
                                            <i class="far fa-comment-alt"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#voice_call">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="btn btn-white call-btn" data-toggle="modal" data-target="#video_call">
                                            <i class="fas fa-video"></i>
                                        </a>
                                    </div>  
                                            <div class="clinic-booking">
                                          <br>
                                                <a class="apt-btn" href="{{$detaillink}}"  style='font-size:10px !important'>Book Appointment</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    
                    <!-- Provider Details Tab -->
                    <div class="card">
                        <div class="card-body pt-0">
                        
                            <!-- Tab Menu -->
                            <nav class="user-tabs mb-4">
                                <ul class="nav nav-tabs nav-tabs-bottom nav-justified">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="#doc_overview" data-toggle="tab">Overview</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#doc_locations" data-toggle="tab">Locations</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#doc_reviews" data-toggle="tab">Reviews</a>
                                    </li>
                                   
                                </ul>
                            </nav>
                            <!-- /Tab Menu -->
                            
                            <!-- Tab Content -->
                            <div class="tab-content pt-0">
                            
                                <!-- Overview Content -->
                                <div role="tabpanel" id="doc_overview" class="tab-pane fade show active">
                                    <div class="row">
                                        <div class="col-md-12 col-lg-9">
                                        
                                            <!-- About Details -->
                                            <div class="widget about-widget">
                                                <h4 class="widget-title">About Me</h4>
                                                <p>{{$data->about_youself}}</p>
                                            </div>
                                            <!-- /About Details -->
                                             <!-- Education Details -->
                                              <?php if($data->message !=""){ ?>
                                            <div class="widget education-widget">
                                                <h4 class="widget-title">Message</h4>
                                                <p>{{$data->message}}</p>
                                               
                                            </div>
                                             <?php } ?>
                                            <!-- /Education Details -->
                                            
                                            <!-- Education Details -->
                                            <?php if($data->educational_journey !=""){ ?>
                                            <div class="widget education-widget">
                                                <h4 class="widget-title">Education</h4>
                                                <p>{{$data->educational_journey}}</p>
                                               
                                            </div>
                                             <?php } ?>
                                            <?php if($data->ages !=""){ ?>
                                            <div class="widget education-widget">
                                                <h4 class="widget-title">Ages </h4>
                                                <p>{{$data->ages}}</p>
                                               
                                            </div>
                                            <?php } ?>
                                            <!-- /Education Details -->
                                    
                                            <!-- Experience Details -->
                                           
                                
                                        
                                            
                                           
                                            
                                            <!-- Specializations List -->
                                            <div class="service-list">
                                                <h4>Specializations</h4>
                                                <ul class="clearfix">
                                                     <?php $values = explode(", ", $data->specialties);
                                                    foreach($values as $val)
                                                    {
                                                        echo "  <li>$val</li>";
                                                    }
                                                    ?>
                                                   
                                                </ul>
                                            </div>
                                            <!-- /Specializations List -->

                                        </div>
                                    </div>
                                </div>
                                <!-- /Overview Content -->
                                
                                <!-- Locations Content -->
                                <div role="tabpanel" id="doc_locations" class="tab-pane fade">
                                
                                    <!-- Location List -->
                                    <div class="location-list">
                                        <div class="row">
                                        
                                            <!-- Clinic Content -->
                                            <div class="col-md-6">
                                                <div class="clinic-content">
                                                    <h4 class="clinic-name"><a href="#">Clinic Name : {{$data->clinic_name}}</a></h4>
                                                    
                                                   
                                                    <div class="clinic-details mb-0">
                                                        <h5 class="clinic-direction"> <i class="fas fa-map-marker-alt"></i> Address : {{$data->clinic_name}} <br><a href="https://www.google.com/maps?q={{$data->clinic_lat}},{{$data->clinic_long}}" target='_blank'>Get Directions</a></h5>
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Clinic Content -->
                                            
                                           
                                            
                                            <div class="col-md-2">
                                                <div class="consult-price">
                                                   ${{$data->consultation_fee}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Location List -->
                                    
                                    <!-- Location List -->
                                   
                                    <!-- /Location List -->

                                </div>
                                <!-- /Locations Content -->
                                
                                <!-- Reviews Content -->
                                <div role="tabpanel" id="doc_reviews" class="tab-pane fade">
                                
                                    <!-- Review Listing -->
                                    <div class="widget review-listing">
                                        <ul class="comments-list">
                                        
                                           
                                            <?php 
   $fg_reviews="SELECT user_id,rating,comment,created_at FROM `doctor_reviews` where doctor_id='$data->id'";
   $run_reviews=mysqli_query($con,$fg_reviews);
   while($row_reviews=mysqli_fetch_array($run_reviews))
   {
    $created_at=$row_reviews['created_at'];
    $rating=$row_reviews['rating'];
    $comment=$row_reviews['comment'];
    $user_id=$row_reviews['user_id'];

     $fg_doc="SELECT name FROM `doctors` where id=1336685";
     $run_doc=mysqli_query($con,$fg_doc);
     $row_doc=mysqli_fetch_array($run_doc);
     $dcname=$row_doc['name'];

    ?>
                                            <!-- Comment List -->
                                            <li>
                                                <div class="comment">
                                                    <img class="avatar avatar-sm rounded-circle" alt="User Image" src="assets/img/patients/patient2.jpg">
                                                    <div class="comment-body">
                                                        <div class="meta-data">
                                                            <span class="comment-author">{{$dcname}}</span>
                                                            <span class="comment-date">Posted : {{date("d-M-Y", strtotime($created_at))}}</span>
                                                            <div class="review-count rating">
                                                              <?php 
                                                    $count=1;
                                                    for($i=1;$i <= $data->rating;$i++)
                                                    {
                                                       echo "  <i class='fas fa-star filled'></i>"; 
                                                       $count=$count + 1;
                                                    }
                                                    for($i=$count;$i <=5 ; $i++)
                                                    {
                                                        echo '<i class="fas fa-star"></i>';
                                                    }
                                                    ?>
                                                  
                                                    
                                                    <span class="d-inline-block average-rating">({{$data->rating}})</span>
                                                            </div>
                                                        </div>
                                                        <p class="comment-content">
                                                            {{$comment}}
                                                        </p>
                                                        
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- /Comment List -->
                                        <?php } ?>
                                            
                                        </ul>
                                        
                                        
                                        
                                    </div>
                                    <!-- /Review Listing -->
                                
                                    <!-- Write Review -->
                                    <div class="write-review">
                                        <h4>Write a review for <strong>{{$data->name}}</strong></h4>
                                        
                                        <!-- Write Review Form -->
                                        <form>
                                            <div class="form-group">
                                                <label>Review</label>
                                                <div class="star-rating">
                                                    <input id="star-5" type="radio" name="rating" value="star-5">
                                                    <label for="star-5" title="5 stars">
                                                        <i class="active fa fa-star"></i>
                                                    </label>
                                                    <input id="star-4" type="radio" name="rating" value="star-4">
                                                    <label for="star-4" title="4 stars">
                                                        <i class="active fa fa-star"></i>
                                                    </label>
                                                    <input id="star-3" type="radio" name="rating" value="star-3">
                                                    <label for="star-3" title="3 stars">
                                                        <i class="active fa fa-star"></i>
                                                    </label>
                                                    <input id="star-2" type="radio" name="rating" value="star-2">
                                                    <label for="star-2" title="2 stars">
                                                        <i class="active fa fa-star"></i>
                                                    </label>
                                                    <input id="star-1" type="radio" name="rating" value="star-1">
                                                    <label for="star-1" title="1 star">
                                                        <i class="active fa fa-star"></i>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Title of your review</label>
                                                <input class="form-control" type="text" placeholder="If you could say it in one sentence, what would you say?">
                                            </div>
                                            <div class="form-group">
                                                <label>Your review</label>
                                                <textarea id="review_desc" maxlength="100" class="form-control"></textarea>
                                              
                                              <div class="d-flex justify-content-between mt-3"><small class="text-muted"><span id="chars">100</span> characters remaining</small></div>
                                            </div>
                                            <hr>
                                            <div class="form-group">
                                                <div class="terms-accept">
                                                    <div class="custom-checkbox">
                                                       <input type="checkbox" id="terms_accept">
                                                       <label for="terms_accept">I have read and accept <a href="#">Terms &amp; Conditions</a></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="submit-section">
                                                <button type="submit" class="btn btn-primary submit-btn">Add Review</button>
                                            </div>
                                        </form>
                                        <!-- /Write Review Form -->
                                        
                                    </div>
                                    <!-- /Write Review -->
                        
                                </div>
                                <!-- /Reviews Content -->
                                
                                <!-- Business Hours Content -->
                                <div role="tabpanel" id="doc_business_hours" class="tab-pane fade">
                                    <div class="row">
                                        <div class="col-md-6 offset-md-3">
                                        
                                            <!-- Business Hours Widget -->
                                            <div class="widget business-widget">
                                                <div class="widget-content">
                                                    <div class="listing-hours">
                                                        <div class="listing-day current">
                                                            <div class="day">Today <span>5 Nov 2019</span></div>
                                                            <div class="time-items">
                                                                <span class="open-status"><span class="badge bg-success-light">Open Now</span></span>
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Monday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Tuesday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Wednesday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Thursday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Friday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day">
                                                            <div class="day">Saturday</div>
                                                            <div class="time-items">
                                                                <span class="time">07:00 AM - 09:00 PM</span>
                                                            </div>
                                                        </div>
                                                        <div class="listing-day closed">
                                                            <div class="day">Sunday</div>
                                                            <div class="time-items">
                                                                <span class="time"><span class="badge bg-danger-light">Closed</span></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /Business Hours Widget -->
                                    
                                        </div>
                                    </div>
                                </div>
                                <!-- /Business Hours Content -->
                                
                            </div>
                        </div>
                    </div>
                    <!-- /Provider Details Tab -->

                </div>
            </div>      
            <!-- /Page Content -->
            
            @endforeach
            

@endsection