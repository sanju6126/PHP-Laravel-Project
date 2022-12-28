<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'message',
        'blog_id',
        'user_id'        
        
    ];

    // public $appends=[
    //     'human_readable_created_at'
    // ];

    public function user(){
        return $this->belongsto(User::class);
    }

    // public function getHumanReadableCreatedAttribute(){
    //     return $this->created_at->diffForHumans();
    // }



}
