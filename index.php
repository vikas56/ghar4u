<?php
session_start();

include("ajax1.php");
$maxid;$maxid1;$maxno;
include("script/db_con.php");
$uname="SELECT s_num from tbl_index_images where s_num=(SELECT max(s_num) from tbl_image) and project_type='Apartments'";
$retval = mysql_query( $uname);
$num_rows = mysql_num_rows($retval);
if($num_rows>0)
{
while($row = mysql_fetch_array($retval))
{
   $maxid=$row['s_num'];
   $_SESSION['maxid']=$maxid+0;
   
 }
}
else
 $_SESSION['maxid']=0;

$uname1="SELECT s_num from tbl_index_images where s_num=(SELECT max(s_num) from tbl_image) and project_type='Villas'";
$retval1 = mysql_query( $uname1);
$num_rows1 = mysql_num_rows($retval1);
if($num_rows1>0)
{
while($row1 = mysql_fetch_array($retval1))
{
   $maxid1=$row1['s_num'];
   
$_SESSION['maxid1']=$maxid1+0;   
 }
 }
 else
 $_SESSION['maxid1']=0;

$uname2="SELECT s_num from tbl_index_images where s_num=(SELECT max(s_num) from tbl_image) and project_type='Row-Houses'";
$retval3 = mysql_query( $uname2);
$num_rows2 = mysql_num_rows($retval3);
if($num_rows2>0)
{
while($row3 = mysql_fetch_array($retval3))
{
   $maxid2=$row3['s_num'];
   
$_SESSION['maxid2']=$maxid2+0;   
 }
 }
 else
 $_SESSION['maxid2']=0;




$totalrows="SELECT max(s_no) from tbl_index_images";
$retval2 = mysql_query( $totalrows);
while($row2 = mysql_fetch_array($retval2))
{
   $maxno=$row2['max(s_no)'];
   $_SESSION['maxno']=$maxno+0;
 // echo $maxno; 
 }


?>

<!DOCTYPE html>
<html lang="en-US">
<title></title>
<script src="http://code.jquery.com/jquery-1.5.js" type="text/javascript"></script>
<head></head>
<body>
<div id="wrapper-outer" >
  <div id="wrapper">
     
<?php include 'header.php';
$latitude=0;$longitude=0;
if(isset($_GET['search']))
{

$state1=$_POST['state1'];
$city1=$_POST['city1'];
$searchtext=$_POST['searchbyaddress'];

if(($state1=="Select State")&&($city1=="Select City")&&($searchtext==""))
{
$latitude=17.385044;
$longitude=78.486671;
}
else
{
if($state1=="Select State")
$state1="";
if(($city1=="Select City")||($city1=="no cities found"))
$city1="";



$searchbyaddress=$state1.$city1.$searchtext;
$prepAddr = str_replace(' ','+',$searchbyaddress);
 
$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
 
$output= json_decode($geocode);
 
$latitude = $output->results[0]->geometry->location->lat;
$longitude = $output->results[0]->geometry->location->lng;
}
}

else
{
$latitude=17.385044;
$longitude=78.486671;
}


 ?>
<!-- Sample code for google map-->
<link href="css/main.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../../jquery/jquery-1.4.4.min.js"></script>        
    <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
    <script type="text/javascript" src="../../gmap3.js"></script>
   
   
    <script type="text/javascript" src="js/data.js"></script>

    <script type="text/javascript">
	
      var mapMenuOpen = true;
	  
      var shadow = new google.maps.MarkerImage(
        'css/images/marker-images/shadow.png',
        new google.maps.Size(87,50),
        new google.maps.Point(0,0),
        new google.maps.Point(30,50)
      );
      
      function mapMenuClose() {
        if (!mapMenuOpen) {
        $('#haritaPopUp').css('display', 'none');
          mapMenuOpen = true;	

        }
      }


      $(function () {
	
	
        // map initialisation
        $('#anaharita').gmap3({
          map:{
            options: {
              center: [<?php echo $latitude;?>, <?php echo $longitude;?>],
              zoom: 11,
              panControl: true,
              overviewMapControl: true,
              mapTypeControl: true,
              scaleControl: true,
              streetViewControl: true,
              zoomControl: true,
              maxZoom: 18,
              minZoom: 5
            }
          }
        });
        $('#anaharita').gmap3({
          circle:{
			  options:{
			 center: [17.385044,78.486671],
radius: 4 * 1609.344,
strokeColor: '#0000FF',
 strokeOpacity: 0.5,
 strokeWeight: 1.2,
 fillColor: '#d3d3d3',
 fillOpacity: 0.35
			  }
				  }});
        $('#anaharita').gmap3({
          marker:{
            values: list, // from js/data.js
            
            // single marker options
            options: {
              draggable: false,
              shadow: shadow
            },
            // single marker events
            events: {
              mouseover: function (marker, event, context) 
			  {
			  //alert(context);
			  //alert(marker);
                mapMenuOpen = true;
                var map = $(this).gmap3('get');
                var scale = Math.pow(2, map.getZoom());
                var nw = new google.maps.LatLng(
                map.getBounds().getNorthEast().lat(), map.getBounds().getSouthWest().lng());
                var worldCoordinateNW = map.getProjection().fromLatLngToPoint(nw);
                var worldCoordinate = map.getProjection().fromLatLngToPoint(marker.getPosition());
                var pixelOffset = new google.maps.Point(Math.floor((worldCoordinate.x - worldCoordinateNW.x) * scale), Math.floor((worldCoordinate.y - worldCoordinateNW.y) * scale));
                var mapposition = $("#anaharita").position();
                
                if (context.data.type != "merkez") 
				{
				
                  var icerik = "<div class='left'><div class='magazaadi' id='magazaadi'><a href=microsite.php?id="+context.data.brosur+"> " + context.data.project_name + "</div><div class='magazaadres' id='magazaadres'> " + context.data.developer_name + "</div><div class='magazabilgi' id='magazabilgi'>" + context.data.Location + "</div></div><div class='right'><div class='urunlogo'><a href=microsite.php?id="+context.data.brosur+"> <img id='magazabrosur' src=imagedisplay.php?maxid='"+context.data.brosur+"' width='100' height='141'></div></div>";
                  
                  $(".accordion").html(icerik);
                  
                  magazalogopath = "logo/";
                  magazabrosurpath = "";
                  
                  $("#haritaPopUp").css('display', 'block');
                  $("#haritaPopUp").css('left', (pixelOffset.x + mapposition.left + 15 + 'px'));
                  $("#haritaPopUp").css('top', (pixelOffset.y + mapposition.top - 62 + 'px'));
                  
                }
              },
              mouseout: function () {
                mapMenuOpen = false;
                var t = setTimeout("mapMenuClose()", 500)
              }
            },
            
            // cluster definition
            cluster:{
              radius: 100,
              
              events: {
                mouseover: function (cluster, event, context) {
                  var icerik = "";
                  magazalogopath = "logo/";
                  magazabrosurpath = "";
              
				  
                  $(".accordion").html(icerik);
                  $("#haritaPopUp").css('display', 'block');
                  $("#haritaPopUp").css('left', ($(cluster.main.getDOMElement()).offset().left + 10 + ($(cluster.main.getDOMElement()).width()/2) +'px'));
                  $("#haritaPopUp").css('top', ($(cluster.main.getDOMElement()).offset().top -30+ ($(cluster.main.getDOMElement()).height()/2)+'px'));
                  
                  $(cluster.main.getDOMElement()).css('border', '0px solid #FF0000');
                  
                  $('.accordionButton').click(function () {
                    $('.accordionButton').removeClass('on');
                    $('.accordionContent').slideUp('normal');
                    if ($(this).next().is(':hidden')) {
                      $(this).addClass('on'); 
                      $(this).next().slideDown('normal');
                    }
                  });
                  
                  $('.accordionContent').hide();
                },
                mouseout: function (cluster, event) {
                  mapMenuOpen = false;
                  var t = setTimeout("mapMenuClose()", 500)
                },
                click: function (cluster, event, context){
                  var map = $(this).gmap3('get');
                  mapMenuOpen = false;
                  var t = setTimeout("mapMenuClose()", 100)				
                  map.setCenter(context.data.latLng);
                  map.setZoom(map.getZoom() + 1);
                }
              }
            }
          }
        });

        $("#haritaPopUp").mouseover(function() {
          mapMenuOpen = true;
          $("#haritaPopUp").css('display', 'block');
        });
        
        $("#haritaPopUp").mouseout(function() {
          $('#haritaPopUp').css('display', 'none');
        });
        
      }); // end of $(function(){
        
       
	  
	   
	   
	   
	    
    </script>
<!-- sample code for google map -->
<div id="wrapper-inner">
<div id="content">
<!-- Google Map -->
<div id="haritaPopUp">
      <div class="arrow"></div>
      <div class="haritaWrapper">
        <div class="top"></div>
        <div class="content">
          <div class="container">
            <div class="accordion"></div>
          </div>
        </div>
      </div>
    </div>
    <div id="haritaMain">
      <div id="haritaSehir">GHAR4U</div>
      <div id="anaharita" class="anaharita"> </div>
    </div>
    <!-- Google Map -->
<div class="container">
    <div id="main">
        <div class="row">
            <div class="span9">
                <h1 class="page-header">LATEST PROPERTIES</h1>
                <div class="properties-grid">
    <div class="row">
       <?php 
	$ptype="";
	$max=0;
	for($n=0;$n<=8;$n++)
	{
//	$max=intval($_SESSION["maxid"])-$n;
	if($n<=2)
	{
$ptype='Apartments';
$max=intval($_SESSION["maxid"])-$n;
}
else if($n<=5)
{
$ptype='Villas';
$max=intval($_SESSION["maxid1"])-$n+3;
}
else if($n<=8)
{
$ptype='Row-Houses';
$max=intval($_SESSION["maxid2"])-$n+6;
}

      echo "<div class='property span3' >
            <div class='image' id='list1' >
                <div class='content'>
                    <a href=microsite.php?maxid=".$max."&p_type=".$ptype."></a>
					<img src=imagedisplay.php?maxid=".$max."&p_type=".$ptype." alt=''></div>
            </div>

            <div class='title'>
                <h2><a href='detail.html'>Residential (".$ptype." )</a></h2>
            </div>";
			
			
include("script/db_con.php");
?>
<script>
function zoom(ele) {
    var id = ele.id;
alert("hello");
    console.log('area element id = ' + id);
  }

/*$('.list1').mouseover(function (e) {
alert("hi");
        var country_id = $(this).attr('id').replace('area_', '');
        $('#' + country_id).css('display', 'block');
    });*/
</script>
<?php 
$retval = mysql_query("SELECT * from tbl_index_images where s_num=".$max." and project_type='".$ptype."'");
while($row = mysql_fetch_array($retval))
{
$retval1 = mysql_query("SELECT * from tmp_tbl_residential_propdetails where prop_id='".$row['prop_id']."' and user_id='".$row['user_id']."' and project_type='".$row['project_type']."'");
while($row1 = mysql_fetch_array($retval1))
{
   echo "<div class='location'>Project Name:  ".$row1['name_of_project']."</div>";
   echo "<div class='location'>Builder Name:  ".$row1['name_of_builder']."</div>";
      echo "<div class='location'>Min:  ".$row1['min_price']. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Max:  ".$row1['max_price']."</div>";
   echo "<div class='location'>Location:  ".$row1['location']."</div>";
}
}

/*echo "<div class='area'>
                
                <span class='value'> <a href='' class='btn btn-primary '>View Details</a></span>
				            </div>
            <div class='bedrooms'><div class='content'>4</div></div>;*/
        echo "</div>";

}?>

      

    
    </div><!-- /.row -->
</div>
            </div>
            <div class="sidebar span3">
                <div class="widget our-agents">
    <div class="title">
        <h2>Our Agents</h2>
    </div><!-- /.title -->

    <div class="content">
        <div class="agent">
            <div class="image">
                <img src="assets/img/Default-small.jpg" alt="">
            </div><!-- /.image -->
            <div class="name">Prasad. S</div><!-- /.name -->
            <div class="phone">+ 91-996600000</div><!-- /.phone -->
            <div class="email"><a href="mailto:sales@ghar4u.com">sales@ghar4u.com</a></div><!-- /.email -->
        </div><!-- /.agent -->

        <div class="agent">
            <div class="image">
                <img src="assets/img/Default-small.jpg" alt="">
            </div><!-- /.image -->
            <div class="name">Prasad. Sadhu</div><!-- /.name -->
            <div class="phone">
+ 91-9951838181</div><!-- /.phone -->
            <div class="email"><a href="mailto:sales@ghar4u.com">
info @ghar4u.com</a></div><!-- /.email -->
        </div><!-- /.agent -->
    </div><!-- /.content -->
</div><!-- /.our-agents -->
                <div class="hidden-tablet">
                    <div class="widget properties last">
    <div class="title">
        <h2>Latest Properties</h2>
    </div><!-- /.title -->

    <div class="content">
        <div class="property">
            <div class="image">
                <a href="detail.html"></a>
                <img src="assets/img/portall-1_21.jpg" alt="">
            </div><!-- /.image -->

            <div class="wrapper">
                <div class="title">
                    <h3>
                       Residential ( Apartment)</h3>
                </div><!-- /.title -->
                <div class="location">Hyderabad</div><!-- /.location -->
                
            </div><!-- /.wrapper -->
        </div><!-- /.property -->

        <div class="property">
            <div class="image">
                <a href="detail.html"></a>
                <img src="assets/img/portall-1_15.jpg" alt="">
            </div><!-- /.image -->

            <div class="wrapper">
                <div class="title">
                    <h3>
                       Residential ( Apartment)</h3>
                </div><!-- /.title -->
                <div class="location">Hyderabad</div><!-- /.location -->
                
            </div><!-- /.wrapper -->
        </div><!-- /.property -->

        <div class="property">
            <div class="image">
                <a href="detail.html"></a>
                <img src="assets/img/portall-1_17.jpg" alt="">
            </div><!-- /.image -->

            <div class="wrapper">
                <div class="title">
                    <h3>
                       Residential ( Apartment)</h3>
                </div><!-- /.title -->
                <div class="location">Hyderabad</div><!-- /.location -->
               
            </div><!-- /.wrapper -->
        </div><!-- /.property -->

        <div class="property">
            <div class="image">
                <a href="detail.html"></a>
                <img src="assets/img/portall-1_19.jpg" alt="">
            </div><!-- /.image -->

            <div class="wrapper">
                <div class="title">
                    <h3>
                       Residential ( Apartment</h3>
                </div><!-- /.title -->
                <div class="location">Hyderabad</div><!-- /.location -->
               
            </div><!-- /.wrapper -->
        </div><!-- /.property -->
    </div><!-- /.content -->
</div><!-- /.properties -->
                </div>
            </div>
        </div>
        <div class="carousel">
    <h2 class="page-header">All properties</h2>

    <div class="content">
        <p><a class="carousel-prev" href="detail.html">Previous</a>
            <a class="carousel-next" href="detail.html">Next</a></p>
        <ul>
          <p>
            <?php 		
		for($i=$maxno;$i>=1;$i--)
		{
		
		$exist="SELECT s_no from tbl_index_images where s_no=".$i;
$r = mysql_query( $exist);
$n_rows = mysql_num_rows($r);
if($n_rows>0)
{
		
		
		echo "<li>
                <div class='image'>
                    <a href='detail.html'></a>
                    <img src=imagedisplay.php?maxid=".$i."  alt=''></div>";
               
                   
				   include("script/db_con.php");
$retval = mysql_query("SELECT * from tbl_index_images where s_no=".$i);
while($row = mysql_fetch_array($retval))
{
$retval1 = mysql_query("SELECT * from tmp_tbl_residential_propdetails where prop_id='".$row['prop_id']."' and user_id='".$row['user_id']."' and project_type='".$row['project_type']."'");
while($row1 = mysql_fetch_array($retval1))
{
  
   echo " <div class='clear'></div><div class='key'>
                    <a href='detail.html'><b>Residential (".$row1['project_type'].")</b></a>
                </div>
				<div class='key'>Project Name:  ".$row1['name_of_project']."</div>
   <div class='key'>Min:  ".$row1['min_price']. "&nbsp;&nbsp;Max:  ".$row1['max_price']."</div>
				<div class='key'>Location:  ".$row1['location']."</div>";
}
}  
	
    	echo "</li>";
			}
}            
            ?>
          </p>
          </ul>
    </div><!-- /.content -->
</div><!-- /.carousel -->        <div class="features">
    <h2 class="page-header">Real Estate Management Services</h2>

    <div class="row">
        <div class="item span4">
            <div class="row">
               

                <div class="text span4">
                    <div class="icon span1">
                    <img src="assets/img/ghar4u_03.png" alt="">
                </div><!-- /.icon --> <h3>Lead/Enquiry Management </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->

        <div class="item span4">
            <div class="row">
                

                <div class="text span4">
                   <div class="icon span1">
                    <img src="assets/img/ghar4u_05.png" alt="">
                </div><!-- /.icon --> <h3>Project Management and Booking </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->

        <div class="item span4">
            <div class="row">
               

                <div class="text span4">
                    <div class="icon span1">
                    <img src="assets/img/ghar4u_08.png" alt="">
                </div><!-- /.icon --> <h3>Rent/Lease Management </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->
    </div>
    <div class="row">
        <div class="item span4">
            <div class="row">
                

                <div class="text span4">
                    <div class="icon span1">
                    <img src="assets/img/ghar4u_13.png" alt="">
                </div><!-- /.icon --><h3>Document Management </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->

        <div class="item span4">
            <div class="row">
                

                <div class="text span4">
                    <div class="icon span1">
                    <img src="assets/img/ghar4u_18.png" alt="">
                </div><!-- /.icon --><h3>Accounting/Finance System </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->

        <div class="item span4">
            <div class="row">
                

                <div class="text span4">
                   <div class="icon span1">
                    <img src="assets/img/ghar4u_15.png" alt="">
                </div><!-- /.icon --> <h3>Social Networking System </h3>
                    <p>Our Inquiry/Lead Management would be helpful for the real estate people to get to know their prospects better and devise actions to convert them into customers.</p>
                </div><!-- /.logo -->
            </div><!-- /.row -->
        </div><!-- /.item -->
    </div><!-- /.row -->
</div><!-- /.features -->    </div>
</div>

  


</div><!-- /#content -->


</div>
<?php include 'fotter.php';?>

</div>

</div>
</body>
</html>

