
<div class="container">
  <div>
  <h2>Find Distance</h2>

  <form class="form-horizontal" role="form" method="post" action="../distance/home">
    <div class="form-group">
      <label class="control-label col-sm-2" for="name">Source:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="source" id="source" maxlength="100" placeholder="Name/Latitude and Longitude"
        <?php
            if( isset( $source ) )
            {
                echo 'value = "' . $source . '""';
            }
        ?>
        required>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label col-sm-2" for="pwd">Destination:</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" name="destination" id="destination" maxlength="100" placeholder="Name/Latitude and Longitude"
        <?php
            if( isset( $destination ) )
            {
                echo 'value = "' . $destination . '""';
            }
        ?>
        required>
      </div>
    </div>

    <?php echo validation_errors(); ?>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default">Submit</button>
      </div>
    </div>

  </form>
  </div>

  <div>
      <?php
          if( !isset($status) )
              return;

          if( 1 == $status )
          {
              if( isset($distance) )
              {
                  echo '<p>The distance between the two points is ' . $distance . '</p>';
              }

              if( isset($duration) )
              {
                  echo '<p>The time to travel between the two points is ' . $duration . '</p>';
              }
          }
          else if( 0 == $status )
          {
              echo '<p>Oops!! We are not able to get the distance between the two points</p>';
          }
          else if( 2 == $status )
          {
              echo '<p>Oops!! We are facing some issues. Please try again...</p>';
          }
      ?>
  </div>
</div>
