<?php

namespace Phobrv\BrvRecruitment\Controllers;

use App\Http\Controllers\Controller;
use Phobrv\BrvCore\Repositories\PostRepository;
use Phobrv\BrvCore\Repositories\TermRepository;
use Phobrv\BrvCore\Repositories\UserRepository;
use Phobrv\BrvCore\Services\UnitServices;
use Auth;
use Phobrv\BrvCore\Services\VString;

use Illuminate\Http\Request;

class RecruitmentController extends Controller {
	protected $userRepository;
	protected $postRepository;
	protected $termRepository;
	protected $unitService;
	protected $type;
	protected $vstring;

	public function __construct(
		VString $vstring,
		UserRepository $userRepository,
		PostRepository $postRepository,
		TermRepository $termRepository,
		UnitServices $unitService) {
		$this->vstring = $vstring;
		$this->userRepository = $userRepository;
		$this->postRepository = $postRepository;
		$this->termRepository = $termRepository;
		$this->unitService = $unitService;
		$this->type = config('option.post_type.recruitment');
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user = Auth::user();
		//Breadcrumb
		$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
			[
				['text' => 'Recruitements', 'href' => ''],
			]
		);
		try {
			$data['posts'] = $this->postRepository->orderBy('created_at', 'desc')->with('user')->all()->where('type', $this->type);
			return view('phobrv::recruitment.index')->with('data', $data);
		} catch (Exception $e) {

		}
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//Breadcrumb
		$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
			[
				['text' => 'Recruitements', 'href' => ''],
				['text' => 'Create', 'href' => ''],
			]
		);

		try {
			return view('phobrv::recruitment.create')->with('data', $data);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$request->merge(['slug' => $this->vstring->standardKeyword($request->title)]);
		$data = $request->all();

		$request->validate(
			[
				'slug' => 'required|unique:posts',
			],
			[
				'slug.unique' => 'Title đã tồn tại',
				'slug.required' => 'Title không được phép để rỗng',
			]
		);

		$data['user_id'] = Auth::id();

		$data['type'] = $this->type;

		$post = $this->postRepository->create($data);
		$this->handleMeta($post, $request);
		$msg = __('Create post success!');

		if ($request->typeSubmit == 'save') {
			return redirect()->route('recruitment.index')->with('alert_success', $msg);
		} else {
			return redirect()->route('recruitment.edit', ['recruitment' => $post->id])->with('alert_success', $msg);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//Breadcrumb
		$data['breadcrumbs'] = $this->unitService->generateBreadcrumbs(
			[
				['text' => 'Recruitements', 'href' => ''],
				['text' => 'Edit', 'href' => ''],
			]
		);

		try {
			$data['post'] = $this->postRepository->find($id);
			$data['meta'] = $this->postRepository->getMeta($data['post']->postMetas);
			return view('phobrv::recruitment.create')->with('data', $data);
		} catch (Exception $e) {
			return back()->with('alert_danger', $e->getMessage());
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		$request->merge(['slug' => $this->vstring->standardKeyword($request->title)]);
		$request->validate(
			[
				'slug' => 'required|unique:posts,slug,' . $id,
			],
			[
				'slug.unique' => 'Title đã tồn tại',
				'slug.required' => 'Title không được phép để rỗng',
			]
		);

		$data = $request->all();
		$post = $this->postRepository->update($data, $id);

		$this->handleMeta($post, $request);

		$msg = __('Update post success!');
		if ($request->typeSubmit == 'save') {
			return redirect()->route('recruitment.index')->with('alert_success', $msg);
		} else {
			return redirect()->route('recruitment.edit', ['recruitment' => $post->id])->with('alert_success', $msg);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$this->postRepository->destroy($id);
		$msg = __("Delete post success!");
		return redirect()->route('recruiment.index')->with('alert_success', $msg);
	}

	public function handleMeta($post, $request) {
		$arrayMeta = [];
		$arrayMeta['number'] = isset($request->number) ? $request->number : '';
		$arrayMeta['office'] = isset($request->office) ? $request->office : '';
		$arrayMeta['rank'] = isset($request->rank) ? $request->rank : '';
		$arrayMeta['degree'] = isset($request->degree) ? $request->degree : '';
		$arrayMeta['wage'] = isset($request->wage) ? $request->wage : '';
		$arrayMeta['endTime'] = isset($request->endTime) ? $request->endTime : '';

		$this->postRepository->insertMeta($post, $arrayMeta);
		$this->postRepository->handleSeoMeta($post, $request);
		$this->postRepository->renderSiteMap();
	}
}
