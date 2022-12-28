<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\support\Facades\Validator;    //this is for your own validation with your type of status code
use App\Models\Blogs;  
use Auth;


class BlogController extends Controller
{
    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:250',
            'post'=>'required',
           'user_id'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation errors',
                'errors' => $validator->messages()
            ],422);
        }

        $blog = Blogs::create([
            'title'=>$request->title,
            'post'=>$request->post,
           //'user_id'=>$request->user()->id
           // 'user_id'=>auth()->user_id,
            'user_id'=>$request->user_id
       ]);


       $blog->load('user');
       return response()->json([
        'message'=>'Blog Successfull Created',
        'data'=> $blog
       ],200);


    }

    public function details ($id){
        $blog=Blogs::with(['user'])->where('id',$id)->first();
        if($blog){
            return response()->json([
                'message'=>'Blog successfully fetched',
                'data'=>$blog
               ],200);
        }else{
            return response()->json([
                'message'=>'no blog found',
               // 'data'=>$blog
               ],400);
        }
    }



    public function update ($id,Request $request){
        $blog=Blogs::with(['user'])->where('id',$id)->first();
        if($blog){
            if($blog->user_id==$request->user()->id){
                    $validator = validator::make($request->all(),[
                        'title'=>'required|max:250',
                        'post'=>'required',
                        'user_id'=>'required'
                    ]);
            
                    if ($validator->fails()){
                        return response()->json([
                            'message'=>'validation errors',
                            'errors'=>$validator->messages()
                        ],422);
                    }
                    $blog->update([
                        'title'=>$request->title,
                        'post'=>$request->post,
                        'user_id'=>$request->user_id
                    ]);
                    return response()->json([
                        'message'=>'Blog Successfully updated',
                        'data'=>$blog
                    ],200);
            } 
            else{
                return response()->json([
                    'message'=>'Access denied'
                ],403);
            
            }
        }
        else{
            return response()->json([
                'message'=>'no blog found',
            // 'data'=>$blog
            ],400);
        }
    }





    public function delete($id,Request $request){
        
        
        $blog=Blogs::where('id',$id)->first();
        if($blog){
             if($blog->user_id==$request->user()->id){
                
                

                $blog->delete();
                return response()->json([
                    'message'=>'Blog Successfull deleted',
                    'data'=>$blog
                ],200);


             }else{
                return response()->json([
                    'message'=>'Access denide',
                    
                ],403);
             }
        }else{
            return response()->json([
                'message'=>'Blog not found',
                
            ],400);
        }

        
    
    }










}
