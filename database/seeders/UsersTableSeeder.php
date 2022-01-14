<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use App\Models\UserSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $names=['LeBron James', 'Nikola Jokic', 'Breadley Beal', 'Luka Doncic', 'James Harden', 'Facundo Campazzo', 'Jrue Holiday', 'Anthony Davis', 'Joel Embiid', 'Ben Simmons', 'Donovan Mitchell', 'Bogdan Bogdanovic', 'Kawhi Leonard', 'Paul George', 'Stephen Curry', 'Marcus Smart', 'Jeff Green', 'Kevin Durant', 'Nemanja Bjelica', 'Adin Vrabac', 'Zion Williamson', 'Klay Thompson', 'Jason Tatum', 'Kemba Walker', 'Trae Young', 'Jamal Murray', 'Carmelo Anthony', 'Tyler Herro', 'Duncan Robinson', 'Jimmy Butler', 'Russell Westbrook', 'Ja Morant', 'Aaron Gordon'];


        for($i=0; $i<count($names); $i++){
            $user=new User();
            $user->name = $names[$i];


            $name_arr=explode(' ', $names[$i]);
            $user->email=strtolower($name_arr[1]).'@gmail.com';
            $user->username=$this->generateUsername($user->name);
            $user->slug=\Illuminate\Support\Str::slug($user->username);
            $user->password=Hash::make('Ii123456/');
            $user->save();
            $this->createUserSettingsRows($user);
            $this->createUserProfileRow($user);
        }

    }

    private function createUserSettingsRows($user){
        $arrKeys=['email_like', 'email_reply', 'email_message','email_news'];
        foreach($arrKeys as $key){
            $userSettings=new UserSettings();
            $userSettings->user_id=$user->id;
            $userSettings->key=$key;
            $userSettings->value=true;
            $userSettings->save();
        }
    }

    private function createUserProfileRow($user){
        $profile=new Profile();
        $profile->user_id=$user->id;
        $profile->about="Something about me...";
        $profile->website="www.fake-website.".str_replace(" ", "-",strtolower($user->name)).".com";
        $profile->save();
    }
    private function generateUsername($name){
        $arr=explode(' ', $name);
        return substr($arr[0], 0,2).'_'.substr($arr[1], 0,4).random_int(10,99);
    }
}
