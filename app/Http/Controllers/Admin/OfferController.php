<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Item;
use App\Models\Brand;
use App\Models\Offer;
use App\Exports\OfferExport;
use App\Services\OfferService;
use App\Http\Requests\OfferRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\OfferResource;
use App\Http\Requests\NewOfferRequest;
use App\Http\Requests\PaginateRequest;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\ChangeImageRequest;
use App\Http\Resources\SimpleOfferListResource;


class OfferController extends AdminController
{

    private OfferService $offerService;

    public function __construct(OfferService $offer)
    {
        parent::__construct();
        $this->offerService = $offer;
       // $this->middleware(['permission:offers'])->only('index', 'export', 'changeImage');
       // $this->middleware(['permission:offers_create'])->only('store');
       // $this->middleware(['permission:offers_edit'])->only('update');
       // $this->middleware(['permission:offers_delete'])->only('destroy');
       // $this->middleware(['permission:offers_show'])->only('show');
    }

    public function index(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Illuminate\Http\Resources\Json\AnonymousResourceCollection | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return SimpleOfferListResource::collection($this->offerService->list($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function store(OfferRequest $request): \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->store($request));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function show(Offer $offer): \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->show($offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function update(OfferRequest $request, Offer $offer): \Illuminate\Http\Response | OfferResource
    {
        try {
            return new OfferResource($this->offerService->update($request, $offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function destroy(Offer $offer): \Illuminate\Http\Response
    {
        try {
            $this->offerService->destroy($offer);
            return response('', 202);
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function export(
        PaginateRequest $request
    ): \Illuminate\Http\Response | \Symfony\Component\HttpFoundation\BinaryFileResponse | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return Excel::download(new OfferExport($this->offerService, $request), 'Offers.xlsx');
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }

    public function changeImage(
        ChangeImageRequest $request,
        Offer $offer
    ): \Illuminate\Http\Response | OfferResource | \Illuminate\Contracts\Foundation\Application | \Illuminate\Contracts\Routing\ResponseFactory {
        try {
            return new OfferResource($this->offerService->changeImage($request, $offer));
        } catch (Exception $exception) {
            return response(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }


    public function NewCreate()
    {
        $items = Item::with('brand')->where('status', 5)->get();
        $brands = Brand::all();
        return view('offers.create', compact('items', 'brands'));
    }
public function NewStore(NewOfferRequest $request)
    {
        try {
            $validated = $request->validate([
                'name'       => 'required|string|max:255',
                'slug'       => 'required|string|max:255|unique:offers,slug',
                'amount'     => 'required|numeric|min:0|max:100',
                'status'     => 'required|in:5,10',
                'start_date' => 'required|date',
                'end_date'   => ['required', 'date', 'after_or_equal:start_date'],
                'brand_id'   => 'required|exists:brands,id',
                'items'      => 'nullable|array',
                'items.*'    => 'exists:items,id',
                'image'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $offer = Offer::create($request->only(['name', 'slug', 'amount', 'status', 'start_date', 'end_date', 'brand_id']));

            if ($request->has('items')) {
                $offer->items()->sync($request->items);
            }

            if ($request->hasFile('image')) {
                $offer->addMediaFromRequest('image')->toMediaCollection('offer');
            }

            return redirect()->route('offers.index')->with('success', 'Offer created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Failed to create offer: ' . $e->getMessage())->withInput();
        }
    }
	
	public function indexNew(PaginateRequest $request)
{
    try {
        $search = $request->query('search');

        $offers = Offer::with(['brand', 'items'])
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%')
                            ->orWhere('slug', 'like', '%' . $search . '%')
                            ->orWhere('amount', 'like', '%' . $search . '%')
                            ->orWhereHas('brand', function ($q) use ($search) {
                                $q->where('name', 'like', '%' . $search . '%');
                            });
            })
            ->orderBy('name', 'asc')
            ->paginate(10);

        return view('offers.index', compact('offers'));
    } catch (Exception $exception) {
        return response(['status' => false, 'message' => $exception->getMessage()], 422);
    }
}
	public function deleteNew(Offer $offer)
    {
        try {
            $this->offerService->destroy($offer);
            return response()->json(['status' => true, 'message' => 'Offer deleted successfully.'], 200);
        } catch (Exception $exception) {
            return response()->json(['status' => false, 'message' => $exception->getMessage()], 422);
        }
    }
}