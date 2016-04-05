<?php
class distance_model extends CI_Model
{

	public function __construct()
	{
		  //$this->load->database();
		  $this->load->helper('url');
		  //$this->load->library('session');
	}

  public function getDistance()
  {
      // all the required form validation shuold be done before we come to this point...
      $source       = $this->input->post('source');
      $destination  = $this->input->post('destination');

      $this->load->database();

      // check to see if already found in database
      $this->db->SELECT('*');
      $this->db->from('distance');
      $query_data = array(
          'user_source'        => $source
          ,'user_destination'  => $destination
      );
      $this->db->where( $query_data );
      $sql = $this->db->get();
      if( $sql->num_rows() == 1 )
      {
          log_message('debug', 'The search data has been found in the DB');
          $sql_result = $sql->result();

          $return_data['status']        = $sql_result[0]->status;
          $return_data['distance'] 		  = $sql_result[0]->distance;
          $return_data['duration'] 		  = $sql_result[0]->duration;
          $return_data['source']   		  = $sql_result[0]->api_source;
          $return_data['destination']   = $sql_result[0]->api_destination;

          return $return_data;
      }

      log_message('debug', 'The search data is not available in the DB');

      $API_KEY      = 'AIzaSyB6ky0s6kmaxH15hsxsNHKuZeI6n_OG2eA';
      $source       = urlencode( $source );
      $destination  = urlencode( $destination );

      $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$source&destinations=$destination&key=$API_KEY";

      //echo $url;
      // Get cURL resource
      $curl = curl_init();

      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HEADER, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_ENCODING, "");

      // Send the request & save response to $resp
      $resp = curl_exec($curl);
      curl_close($curl);

      $maps_data = json_decode($resp);
      $return_data = array();

      // decode the encoded data
      $source       = urldecode( $source );
      $destination  = urldecode( $destination );

      if( "OK" == $maps_data->rows[0]->elements[0]->status )
      {
          $return_data['status']        = 1;
          $return_data['distance'] 		  = $maps_data->rows[0]->elements[0]->distance->text;
          $return_data['duration'] 		  = $maps_data->rows[0]->elements[0]->duration->text;
          $return_data['source']   		  = $maps_data->origin_addresses[0];
          $return_data['destination']   = $maps_data->destination_addresses[0];

          $sql_data = array(
                'status'            => 1
                ,'user_source'      => $source
                ,'user_destination' => $destination
                ,'api_source'       => $maps_data->origin_addresses[0]
                ,'api_destination'  => $maps_data->destination_addresses[0]
                ,'distance'         => $maps_data->rows[0]->elements[0]->distance->text
                ,'duration'         => $maps_data->rows[0]->elements[0]->duration->text
          );
          //print_r($sql_data);

          if( $this->db->insert('distance', $sql_data) ) {
              log_message('debug', 'inserting into the DB is success');
          }
          else {
              #need not worry if insert query fails
              log_message('error', 'inserting into the DB failed');
              log_message('debug', 'inserting into the DB failed');
          }
      }

      else if( "ZERO_RESULTS" == $maps_data->rows[0]->elements[0]->status )
      {
          $return_data['status']   		 = 0;
          $return_data['source']   		 = $maps_data->origin_addresses[0];
          $return_data['destination']  = $maps_data->destination_addresses[0];

          $sql_data = array(
                'status'            => 0
                ,'user_source'      => $source
                ,'user_destination' => $destination
                ,'api_source'       => $maps_data->origin_addresses[0]
                ,'api_destination'  => $maps_data->destination_addresses[0]
          );

          if( $this->db->insert('distance', $sql_data) ) {
              log_message('info', 'inserting into the DB is success');
          }
          else {
              #need not worry if insert query fails
              log_message('error', 'inserting into the DB failed');
              log_message('debug', 'inserting into the DB failed');
          }

      }
      else {
          #set the status as 2 and return
          log_message('error', 'An improper state has been hit');
          log_message('debug', 'An improper state has been hit');
          $return_data['status']   	= 2;
      }

      return $return_data;
  }
}
