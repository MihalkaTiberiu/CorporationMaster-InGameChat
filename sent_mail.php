<?php
//get the current session
@session_start();

//connect to the db
require(__DIR__.'/db_connect.php');
$user = $_SESSION['user_name'];

	
	
	function timepassed ($time)
	{
	    $time = time() - $time; 
	    $timeident = array (31536000 => 'year', 2592000 => 'month', 604800 => 'week', 86400 => 'day', 3600 => 'hour', 60 => 'minute',   1 => 'second'
	    );
	    foreach ($timeident as $unit => $text) {
	        if ($time < $unit) continue;
	        $numberOfUnits = floor($time / $unit);
	        return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
	    }
	}
	
	//query the received messages
	$received_query = mysqli_query($link,"SELECT MAX(time)AS time, message, sender,id, receiver FROM messages WHERE sender = '".$user."' GROUP BY receiver ORDER BY time DESC LIMIT 20") or die("failed m ". mysqli_error());

	
	
if (mysqli_num_rows($received_query) >0) {  
	

?>
<div class="block block-transparent nm" style="max-height: 50px;"><div class="scroll-mail email-list">
<?php



while($row = mysqli_fetch_array($received_query) ){
		$sender = $row['sender'];
		$receiver = $row['receiver'];
                $message = mb_strimwidth($row['message'], 0, 30, "...");
		$date = $row['time'];
		$seen_flag = $row['seen_flag'];
		$id = $row ['id'];
		
		?>

	
                       
                    

                        <div class="email-list-item" onClick="reply('<?php echo $id; ?>','<?php echo $receiver; ?>')">
                            <div class="item-line">
                                <div class="item-line-box">
                                    
                                </div>
                                <div class="item-line-title">Message to: <?php echo  $receiver;  ?></div>
                                <div class="item-line-date"> 
                                     <?php if ($seen_flag == 0) {  ?>  <span class="icon-stop text-warning tipb" data-original-title="New Message"></span> <?php } echo timepassed($date) .' ago' ?>
                                </div>
                            </div>
                            <div class="item-line">
                                <div class="item-line-box">
                                    <span class="icon-star"></span>
                                </div>
                                <div class="item-line-title"><?php echo  $message;  ?></div>
                               
                            </div>
                            
                        </div>
                       
                                                                                  
                                              
 	
		
 <?php
	} //end while
	
?>
</div> </div> 

<?php } else {

?>
<div class="clearfix"></div>
<div class="alert alert-info text-center"> You have not sent any messages</div>

<?php } ?>


<script type="text/javascript">

    function reply(id,sender) {
        
            $('.maincontent').css('display', 'block');
            $('.maincontent').html('<img src="ajax-loader.gif" alt="Loading" />');
            var values = 'id='+id+'&sender='+sender;
            $.ajax({
                url: "chat/reply.php",
                type: "POST",
                datatype: "html",
                data: values,
                success: function (data) {
                    $('.maincontent').html(data);
                    
                  

                },
                error: function () {
                  
                    $('.maincontent').html('');
                     _toastr('Server error. Please log in again!', "top-full-width","danger",false);
	          
	        

                }
            });
            return false;
    }
</script>