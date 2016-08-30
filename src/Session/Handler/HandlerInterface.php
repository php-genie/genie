<?php
namespace Genie\Session\Contract;
/**
* 
*/
interface HandlerInterface
{
	public close (  );
	public create_sid (  );
	public destroy ( $session_id );
	public gc ( int $maxlifetime );
	public open ( $save_path , $session_name );
	public read (  $session_id );
	public write ( $session_id , $session_data );
}
