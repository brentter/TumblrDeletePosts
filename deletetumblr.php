<head>
	<link rel="stylesheet" href="screen.css" media="screen" />

</head><body></body>
<div id="container">

<form id="form1" action="" method="post">	
		
			<fieldset><legend>Tumblr Delete Posts</legend>
				<p>
					<label for="email">Email</label>
					<input type="text" name="tumblrEmail" id="tumblrEmail" size="30" />
				</p>
				<p>
					<label for="password">Password</label>
					<input type="text" name="tumblrPassword" id="tumblrPassword" size="30" />
				</p>			
																					
				<p>
					<label for="tumblrdomain">YOUR-TUMBLR-DOMAIN</label>
					<input type="text" name="tumblrDomain" id="tumblrDomain" size="30">
				</p>								
			</fieldset>					

			<p class="submit"><button type="submit">Send</button></p>		
						
		</form>	</div></body>
<?php
error_reporting(0);

if ( !empty($_POST) )
{

// Tumblr Blog Info
$tumblrEmail    = $_POST['tumblrEmail'];
$tumblrPassword = $_POST['tumblrPassword'];
$tumblrDomain   = $_POST['tumblrDomain'];
$i = 0;

do {
    
    $xml = simplexml_load_file("http://" . $tumblrDomain . ".tumblr.com/api/read?start=" . $i . "&num=50&rand=" . rand());
    
    $i      += 50;
    $count  = count($xml->posts->post);
    
    if($count == 0)
        break;

    foreach($xml->posts->post as $post) {
        
        $postId = (string)$post[id];
        
        //echo $postId . "\n";

        $request_data = http_build_query(
            array(
                'email'     => $tumblrEmail,
                'password'  => $tumblrPassword,
                'post-id'   => $postId,
                'generator' => 'API example'
            )
        );

        // Send the POST request (with cURL)
        $c = curl_init('http://www.tumblr.com/api/delete');
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($c);
        $status = curl_getinfo($c, CURLINFO_HTTP_CODE);
        curl_close($c);

        // Check for success
        if ($status == 201)
            echo "Success! The new post ID is $result.\n";
        else if ($status == 403)
            echo "Bad email or password\n";
        else
           
    }
    
} while($count > 0);
echo "DONE! ALL POSTS DELETED!";
}
?>