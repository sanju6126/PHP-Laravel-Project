<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\support\Facades\Validator;    //this is for your own validation with your type of status code

use App\Models\Blogs;
use App\Models\Comment;


class CommentController extends Controller
{
    public function create($blog_id,Request $request){
        $blog=Blogs::where('id',$blog_id)->first();
        if($blog){
            $validator = Validator::make($request->all(),[
                'message'=>'required',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message'=>'validation errors',
                    'errors'=>$validator->messages()
                ],422);
            }

            $comment = Comment::create([
                'message' => $request->message,
                'blog_id'=>$blog->id,
                'user_id'=>$request->user()->id
            ]);

            $comment->load('user');

            return response()->json([
                'message'=>'Comment Created',
                'data'=>$comment
            ],200);

        }
        else{
             return response()->json([
                'message'=>'No Blog found',

             ],400);
        }
    }

    public function update($comment_id,Request $request){
        $comment=Comment::with(['user'])->where('id',$comment_id)->first();
    
        if($comment){
            if($comment->user_id==$request->user()->id){
                $validator = Validator::make($request->all(),[
                    'message'=>'required',
                ]);

                if($validator->fails()){
                    return response()->json([
                        'message'=>'Validation Errors',
                        'errors'=>$validator->messages()
                    ],422);
                }

                $comment->update([
                    'message'=>$request->message
                ]);
                return response()->json([
                    'message'=>'Comment successfully updated',
                    'data'=>$comment
                ],200);


            }
            else{
                return response()->json([
                    'message'=>'Access Denied',
                ],403);
            }
        }
        else{
            return response()->json([
                'message'=>'No comment found',
            ],400);
        }


    }


    
    public function delete($comment_id,Request $request){
        $comment=Comment::where('id',$comment_id)->first();
    
        if($comment){
            if($comment->user_id==$request->user()->id){
                $comment->delete();
                return response()->json([
                    'message'=>'Comment successfully Deleted',
                ],200);


            }
            else{
                return response()->json([
                    'message'=>'Access Denied',
                ],403);
            }
        }
        else{
            return response()->json([
                'message'=>'No comment found',
            ],400);
        }
    } 





}
