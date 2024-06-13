<?php

namespace App\Http\Controllers;

use App\Models\Polygons;
use Illuminate\Http\Request;

class PolygonController extends Controller
{
    public function __construct()
    {
        $this->polygon = new Polygons();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $polygons = $this->polygon->polygons();

        foreach ($polygons as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature
        ]);
    }

    // buat bikin polygon
    public function create()
    {
        //
    }

    // buat nyimpen hasil pembuatan polygon
    public function store(Request $request)
    {
        //validate
        $request->validate(
            [
                'name' => 'required',
                'geom' => 'required',
                'image' => 'mimes:jpeg,png,jpg,tiff,gif|max:10000'
            ],
            [
                'name.required' => 'Name is required.',
                'geom.required' => 'Location is required',
                'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, tiff, gif.',
                'image.max' => 'Image size may not be greater than 10MB.'
            ]
        );

        //create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }
        //upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_polygon.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);
        } else {
            $filename = null;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom,
            'image' => $filename
        ];


        //create polygon
        if (!$this->polygon->create($data)) {
            return redirect()->back()->with('error', 'Failed to create polygon!');
        }

        //redirect to map
        return redirect()->back()->with('success', 'Polygon created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $polygons = $this->polygon->polygon($id);

        foreach ($polygons as $p) {
            $feature[] = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $feature
        ]);
    }
    public function edit(string $id)
    {
        $polygon = $this->polygon->find($id);

        $data = [
            'title' => 'Edit Polygon',
            'polygon' => $polygon,
            'id' => $id
        ];

        return view('edit-polygon', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //validate
        $request->validate(
            [
                'name' => 'required',
                'geom' => 'required',
                'image' => 'mimes:jpeg,png,jpg,tiff,gif|max:10000'
            ],
            [
                'name.required' => 'Name is required.',
                'geom.required' => 'Location is required',
                'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, tiff, gif.',
                'image.max' => 'Image size may not be greater than 10MB.'
            ]
        );

        //create folder images
        if (!is_dir('storage/images')) {
            mkdir('storage/images', 0777);
        }
        //upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_polygon.' . $image->getClientOriginalExtension();
            $image->move('storage/images', $filename);

            //delete image
            $image_old = $request->image_old;
            if ($image_old !=null) {
                unlink('storage/images/' . $image_old);
            }


        } else {
            $filename = $request->image_old;
        }

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'geom' => $request->geom,
            'image' => $filename
        ];


        //update polygon
        if (!$this->polygon->find($id)->update($data)) {
            return redirect()->back()->with('error', 'Failed to update polygon!');
        }

        //redirect to map
        return redirect()->back()->with('success', 'Polygon updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //get image
        $image = $this->polygon->find($id)->image;

        //delete image
        if ($image != null) {
            unlink('storage/images/' . $image);
        }
        //delete polygon
        if (!$this->polygon->destroy($id)) {
            return redirect()->back()->with('error', 'Failed to delete polygon');
        }
        //redirect to map
        return redirect()->back()->with('success', 'Polygon deleted successfully');
    }

    public function table()
    {
        $polygons = $this->polygon->all();

        $data = [
            'title' => 'Table Polygon',
            'polygons' => $polygons
        ];

        return view('table-polygon', $data);
    }

    public function outputPolygon()
    {
        $polygons = $this->polygon->polygons();

        $data = [
            'title' => 'Output Polygon',
            'polygons' => $polygons
        ];

        return view('output-polygon', $data);
    }




}
