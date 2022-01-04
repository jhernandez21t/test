<?php
require_once('vendor/autoload.php');
$mailchimp = new \MailchimpMarketing\ApiClient();
$mailchimp->setConfig([
'apiKey' => '86d744db87afd5e0bbb6fb3ebf54e246-us5',
'server' => 'us5'
]);
//create_list($mailchimp);
//testconexion($mailchimp);
//$response = $mailchimp->lists->getAllLists(); // obtiene audiciencias
getAllLists($mailchimp);


/*
$info = array (       agregar un contacto a una lista o audiencia
  'id_list' => 'b2b57a71e8',
  'email' => 'manuel_hg_1@outlook.com',
  'name' => 'Juan Manuel',
  'lastname' => 'HG',
  'tags' => array('telat','administrativo')
);
add_contact_to_audence($mailchimp,$info);*/

/*$data = array ( //verifica si el usuario esta dado de alta y obtiene sus datos
    'email' => 'manuel_hg_1@outtlook.com',
    'id_list' => 'b2b57a71e8'
);
verify_account($mailchimp,$data);*/
/*
$data = array(
  'email' => 'manuel_hg_1@outlook.com',
  'id_list' => 'b2b57a71e8'
);

unsuscribe_contact($mailchimp,$data);*/
/* actualizar tag */
/*$data = array(
  'email' => 'manuel_hg_1@outlook.com',
  'id_list' => 'b2b57a71e8',
  'tag' => 'test',
  'status' => 'inactive' //active to add inactive to remove
);
update_tag($mailchimp,$data);*/

/*$data = array(
  'email' => 'manuel_hg_1@outlook.com',
  'id_list' => 'b2b57a71e8'
 );

get_contact_tags($mailchimp,$data);
*/




function testconexion($mailchimp)
{
  $response = $mailchimp->ping->get();              /*->valida la conexion "   "health_status": "Everything's Chimpy!""*/
  print_r($response);
}

function getAllLists($mailchimp)
{
  $response = $mailchimp->lists->getAllLists();
  foreach($response as $value){
    echo json_encode($value->name)."<br><br>";
  }
}

function create_an_audience($client,$data)
{
  try {
    $response = $mailchimp->lists->createList([
      "name" => "PHP Developers Meetup",
      "permission_reminder" => "permission_reminder",
      "email_type_option" => false,
      "contact" => [
        "company" => "Mailchimp",
        "address1" => "675 Ponce de Leon Ave NE",
        "city" => "Atlanta",
        "state" => "GA",
        "zip" => "30308",
        "country" => "US",
      ],
      "campaign_defaults" => [
        "from_name" => "Gettin' Together",
        "from_email" => "gettingtogether@example.com",
        "subject" => "PHP Developer's Meetup",
        "language" => "EN_US",
      ],
    ]);
    print_r($response);
  } catch (MailchimpMarketing\ApiException $e) {
    echo $e->getMessage();
  }
}

function add_contact_to_audence($mailchimp,$info)
{
  try {
      $response = $mailchimp->lists->addListMember($info['id_list'], [
          "email_address" => $info['email'],
          "status" => "subscribed",
          "merge_fields" => [
            "FNAME" => $info['name'],
            "LNAME" => $info['lastname']
          ],
          "tags" => $info['tags']
      ]);
      print_r($response);
  } catch (MailchimpMarketing\ApiException $e) {
      echo $e->getMessage();
  }
}

function verify_account($mailchimp,$data)
{
  $subscriber_hash = md5(strtolower($data['email']));

  try {
      $response = $mailchimp->lists->getListMember($data['id_list'], $subscriber_hash);
      print_r($response);
  } catch (MailchimpMarketing\ApiException $e) {
      echo $e->getMessage();
  }
}

function unsuscribe_contact($mailchimp,$data)
{
  $subscriberHash = md5(strtolower($data['email']));
  try {
      $response = $mailchimp->lists->updateListMember($data['id_list'], $subscriberHash, ["status" => "unsubscribed"]);
      print_r($response);
  } catch (MailchimpMarketing\ApiException $e) {
      echo $e->getMessage();
  }
}

function update_tag($mailchimp,$data)
{
  try {
  $subscriber_hash = md5(strtolower($data['email']));
  $mailchimp->lists->updateListMemberTags($data['id_list'], $subscriber_hash, [
    "tags" => [
      [
        "name" => $data['tag'],
        "status" => $data['status']
      ]
    ]
  ]);
      echo "The return type for this endpoint is null";
  } catch (MailchimpMarketing\ApiException $e) {
      echo $e->getMessage();
  }
}

function get_contact_tags($mailchimp,$data)
{
  $subscriber_hash = md5(strtolower($data['email']));
  try {
      $response = $mailchimp->lists->getListMemberTags($data['id_list'], $subscriber_hash);
      foreach ($response->tags as $value) {
        echo json_encode($value)."<br>";
      }
  } catch (MailchimpMarketing\ApiException $e) {
      echo $e->getMessage();
  }
}


?>
