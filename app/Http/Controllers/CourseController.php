<?php

namespace App\Http\Controllers;

use App\Course;
use App\Thread;
use App\ThreadReply;
use App\UserCourse;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (Auth::user()->userDetail->role != 'Instructor') {
            abort(403, 'Permission denied');
        }

        $post = Course::create(
            array_merge(
                $request->only([
                    'name',
                    'description',
                ]),
                [
                    'user_id' => Auth::id(),
                ]
            )
        );
        return response()->json([
            'message' => 'Create course successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (Auth::user()->userDetail->role != 'Users') {
            abort(403, 'Permission denied');
        }

        DB::beginTransaction();
        try {
            UserCourse::create(
                [
                    'course_id' => $request->input('course_id'),
                    'user_id' => Auth::id(),
                ]
            );
            DB::commit();
            return response()->json([
                'message' => 'Register course successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createThread(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (Auth::user()->userDetail->role != 'Instructor') {
            abort(403, 'Permission denied');
        }

        DB::beginTransaction();
        try {
            $thread = Thread::create(
                [
                    'content' => $request->input('content'),
                    'course_id' => $request->input('course_id'),
                    'user_id' => Auth::id(),
                ]
            );
            DB::commit();
            return response()->json([
                'message' => 'Create thread successfully',
                'thread' => $thread,
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createThreadReply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thread_id' => 'required|exists:threads,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        if (Auth::user()->userDetail->role != 'Users') {
            abort(403, 'Permission denied');
        }

        DB::beginTransaction();
        try {
            $thread = ThreadReply::create(
                [
                    'content' => $request->input('content'),
                    'thread_id' => $request->input('thread_id'),
                    'user_id' => Auth::id(),
                ]
            );
            DB::commit();
            return response()->json([
                'message' => 'Reply thread successfully',
                'thread' => $thread,
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listThreads(Request $request)
    {
        if (Auth::user()->userDetail->role != 'Users') {
            abort(403, 'Permission denied');
        }

        $threads = Thread::select('threads.*')
            ->join('user_courses', 'user_courses.course_id', 'threads.course_id')
            ->where('user_courses.user_id', Auth::id())
            ->get();

        return response()->json([
            'message' => 'Register course successfully',
            'threads' => $threads,
        ], 200);
    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteThreadReply(Request $request, int $id)
    {
        DB::beginTransaction();
        try {
            $role = Auth::user()->userDetail->role;
            $threadReply = ThreadReply::whereId($id)->first();
            if ($role == 'Instructor' || ($role == 'Users' && $threadReply->user_id == Auth::id())) {
                $threadReply->delete();
            }
            DB::commit();
            return response()->json([
                'message' => 'Delete thread reply successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error',
            ], 500);
        }

    }

    /**
     * Create a course.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, int $id)
    {
        if (Auth::user()->userDetail->role != 'Admin') {
            abort(403, 'Permission denied');
        }
        DB::beginTransaction();
        try {
            Course::whereId($id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'Delete source successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Error',
            ], 500);
        }
    }
}
