

@include('admin::layouts.includes.header')

<!-- page content -->
<div class="right_col" role="main">
  <!-- top tiles -->
  <div class="row section-gap">
    <!-- Overview Cards -->
<div class="heading">
<h4 class="sub-caption">Overview</h4>
    <!-- The form -->
<!-- <form class="search" action="action_page.php">
  <input type="text" placeholder="Search.." name="search">
  <button type="submit"><i class="fa fa-search"></i></button>
</form> -->
</div>
    
    <div class="flex-box">      
      <!-- Card -->
    <!-- <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
            <span class="card-icons"><i class="fa fa-th-list" aria-hidden="true"></i></span>Active Events
          </div>
          <div class="count">{{$events}}</div>
        </div>
      </div> -->
      <!-- END Card -->
  
      <!-- Card -->
      <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-list" aria-hidden="true"></i></span> Main Categories
          </div>
          <div class="count">{{$categories}}</div>
          
        </div>
      </div>
      <!-- END Card -->
  
      <!-- Card -->
      <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-cubes" aria-hidden="true"></i></span> Total Products
          </div>
          <div class="count">{{$products}}</div>
        </div>
      </div>
      <!-- END Card -->
  
  
      <!-- Card -->
      <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-list" aria-hidden="true"></i></span> Total Cities
          </div>
          <div class="count">{{$cities}}</div>
        </div>
      </div>
      <!-- END Card -->
  
  
      <!-- Card -->
      <!-- <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-users" aria-hidden="true"></i> </span>Total Sellers
          </div>
          <div class="count">20</div>
        </div>
      </div> -->
      <!-- END Card -->
  
  
      <!-- Card -->
      <!-- <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-user" aria-hidden="true"></i></span> Total Users
          </div>
          <div class="count">200</div>
        </div>
      </div> -->
      <!-- END Card -->


      <!-- Card -->
     <!--  <div class="col-xs-6 col-md-4 mb-20">
        <div class="card-container status-spot">
          <div class="card-title">
              <span class="card-icons"><i class="fa fa-user" aria-hidden="true"></i></span> Total Live Users
          </div>
          <div class="count">300</div>
        </div>
      </div> -->
      <!-- END Card -->
    </div>


    <!-- END Overviews -->

  </div>
</div>
@include('admin::layouts.includes.footer')