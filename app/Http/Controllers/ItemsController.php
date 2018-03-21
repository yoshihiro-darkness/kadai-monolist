<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Item;

class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $keyword = request()->keyword;
		$items = [];
		if ($keyword) {
			$client =  new \RakutenRws_Client();
			$client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));

			$rws_response = $client->execute('IchibaItemSearch', [
				'keyword' => $keyword,
				'imageFlag' => 1,
				'hits' => 20,
			]);

			foreach ($rws_response->getData()['Items'] as $rws_item) {
				$item = new \App\Item();
				$item->code = $rws_item['Item']['itemCode']; 
				$item->name = $rws_item['Item']['itemName']; 
				$item->url = $rws_item['Item']['itemUrl']; 
				$item->image_url = str_replace('?_ex=128x128', '', $rws_item['Item']['mediumImageUrls'][0]['imageUrl']);
				$items[] = $item;
			}
		}

		return view('items.create', [
			'keyword' => $keyword,
			'items' => $items,
		]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       	$item = Item::find($id);
		$want_users = $item->want_users;
		$have_users = $item->have_users;

		return view('items.show', [
			'item' => $item,
			'want_users' => $want_users,
			'have_users' => $have_users,
		]); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
	public function want()
	{
		$itemCode = request()->itemCode;

		// itemCodeから商品を検索
		$client = new \RakutenRws_Client();
		$client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
		$rws_response = $client->execute('IchibaItemSearch', [
			'itemCode' => $itemCode,
		]);
		$rws_item = $rws_response->getData()['Items'][0]['Item'];

		// Item 保守 or 検索（見つかると作成せずにそのインスタンスを取得する）
		$item = Item::firstOrCreate([
			'code' => $rws_item['itemCode'],
			'name' => $rws_item['itemName'],
			'url' => $rws_item['itemUrl'],
			'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl'])
		]);

		\Auth::user()->want($item->id);

		return redirect()->back();
	}
	public function dont_want()
	{
		$itemCode = request()->itemCode;

		if (\Auth::user()->is_wanting($itemCode)) {
			$itemId = Item::where('code', $itemCode)->first()->id;
			\Auth::user()->dont_want($itemId);
		}
		return redirect()->back();
	}
	public function have()
	{
		$itemCode = request()->itemCode;

		// itemCodeから商品を検索
		$client = new \RakutenRws_Client();
		$client->setApplicationId(env('RAKUTEN_APPLICATION_ID'));
		$rws_response = $client->execute('IchibaItemSearch', [
			'itemCode' => $itemCode,
		]);
		$rws_item = $rws_response->getData()['Items'][0]['Item'];

		// Item 保守 or 検索（見つかると作成せずにそのインスタンスを取得する）
		$item = Item::firstOrCreate([
			'code' => $rws_item['itemCode'],
			'name' => $rws_item['itemName'],
			'url' => $rws_item['itemUrl'],
			'image_url' => str_replace('?_ex=128x128', '', $rws_item['mediumImageUrls'][0]['imageUrl'])
		]);

		\Auth::user()->have($item->id);

		return redirect()->back();
	}
	public function dont_have()
	{
		$itemCode = request()->itemCode;

		if (\Auth::user()->is_having($itemCode)) {
			$itemId = Item::where('code', $itemCode)->first()->id;
			\Auth::user()->dont_have($itemId);
		}
		return redirect()->back();
	}
}
