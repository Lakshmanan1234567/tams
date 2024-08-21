<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use App\Models\general;
use App\Master\PostalCode;
use App\Models\ServerSideProcess;
use App\Models\DocNum;
use App\Rules\ValidUnique;
use App\Rules\ValidDB;

class generalController extends Controller{
	private $general;
	private $UserID;
	private $Settings;
	private $Menus;
	private $DocNum;
    public function __construct(){
        $this->middleware('auth');
		$this->DocNum=new DocNum();
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,"");
			$this->Settings=$this->general->getSettings();
			$this->Menus=$this->general->loadMenu();
			return $next($request);
		});
	}
	public function getMenus(Request $req){
		return $this->Menus;
	}
	public function getMenuData(request $req){
		return $this->general->getMenus(array("Level"=>"L001"));
	}
	
	public function ThemeUpdate(Request $req){
		try {
			$Theme=json_decode($req->Theme,true);
			if(is_array($Theme)){
				foreach ($Theme as $key => $value) {
					$result=DB::table('tbl_user_theme')->where('UserID',$this->UserID)->where('Theme_option',$key)->get();
					if(count($result)>0){
						$data=array($key=>$value);
						DB::table('tbl_user_theme')->where('UserID',$this->UserID)->where('Theme_option',$key)->update(array("Theme_Value" => $value));
					}else{
						DB::table('tbl_user_theme')->insert(array('UserID'=>$this->UserID,'Theme_option'=>$key,"Theme_Value" => $value));
					}
				}
			}
			
		} catch (Exception $e) {
			
		}
	}
	public function GetCountry(request $req){
		$return=array();
		$result=DB::table('tbl_countries')->where('ActiveStatus',1)->where('DFlag',0)->orderBy('CountryName','asc')->get();
		return $result;
	}
	public function GetState(request $req){
		$return=array();
		$result=DB::table('tbl_states')->where('CountryID',$req->CountryID)->orderBy('StateName','asc')->where('ActiveStatus',1)->where('DFlag',0)->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("StateID"=>$result[$i]->StateID,"StateName"=>$result[$i]->StateName,"CountryID"=>$result[$i]->CountryID);
		}
		return $return;
	}
	public function GetCity(request $req){
		$return=array();
		$result=DB::table('tbl_Mdi')->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("CityID"=>$result[$i]->DID,"CityName"=>$result[$i]->DName);
		}
		return $return;
	}
	public function GetTaluka(request $req){
		$return=array();
		$result=DB::table('tbl_MdT')->where('DName',$req->CityID)->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("CityID"=>$result[$i]->DID,"CityName"=>$result[$i]->CName);
		}
		return $return;
	}
	public function GetBlock(request $req){
		$return=array();
		$result=DB::table('tbl_Mdbvp')->where('DName',$req->CityID)->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("CityID"=>$result[$i]->DID,"CityName"=>$result[$i]->BName);
		}
		return $return;
	}
	public function GetVillage(request $req){
		$return=array();
		$result=DB::table('tbl_Mdbvp')->where('DName',$req->CityID)->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("CityID"=>$result[$i]->DID,"CityName"=>$result[$i]->vpName);
		}
		return $return;
	}
	public function Getpincode(request $req){
		$return=array();
		$result=DB::table('tbl_mdp')->where('DName',$req->CityID)->get();
		for($i=0;$i<count($result);$i++){
			$return[]=array("CityID"=>$result[$i]->DID,"CityName"=>$result[$i]->PINNAME);
		}
		return $return;
	}
	public function getPostalCode(request $req){
		$return=array();
		
		$result=DB::table('tbl_postalcodes')->where('CountryID',$req->CountryID)->where('StateID',$req->StateID)->where('ActiveStatus',1)->where('DFlag',0)->get();
		return $result;
	}
	public function GetGender(request $req){
		$return=array();
		
		$result=DB::table('tbl_genders')->where('ActiveStatus',1)->where('DFlag',0)->get();

		return $result;
	}
	public function RoleData(){
		$data=DB::Table('tbl_user_roles')->Where("DFlag",0)->get();
	
		return $data;
    }
	
}
