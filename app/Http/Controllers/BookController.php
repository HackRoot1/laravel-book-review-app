<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BookController extends Controller
{
    // This method will show books listing page
    public function index(Request $request) {
        
        $books = Book::orderBy('created_at', 'DESC');
 
        if(!empty($request->keyword)) {
            $books->where('title', 'like', '%'.$request->keyword.'%');
        }

        $books = $books->withCount('reviews')->withSum('reviews', 'rating')->paginate(10);

        return view('books.list', [
            'books' => $books
        ]);
    }

    // This method will show create book page 
    public function create() {
        return view('books.create');
    }
    
    // This method will a book in database 
    public function store(Request $request) {

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];

        if(!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect()->route('books.create')->withInput()->withErrors($validator);
        }

        // save book in database 
        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        // upload book here
        if(!empty($request->image)) {
            // $image = $request->file('image');
            
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->move(public_path('uploads/books'), $imageName);
            
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'. $imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }

        return redirect()->route('books.index')->with('success', 'Book added successfully.');

    }
    
    // This method will show edit book page
    public function edit($id) {
        $book = Book::findOrFail($id);
        return view('books.edit', ['book' => $book]);
    }
    
    // This method will update a book
    public function update($id, Request $request) {
        $book = Book::findOrFail($id);

        $rules = [
            'title' => 'required|min:5',
            'author' => 'required|min:3',
            'status' => 'required',
        ];

        if(!empty($request->image)) {
            $rules['image'] = 'image';
        }

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect()->route('books.edit', $book->id)->withInput()->withErrors($validator);
        }

        // save book in database 
        $book->title = $request->title;
        $book->description = $request->description;
        $book->author = $request->author;
        $book->status = $request->status;
        $book->save();

        // upload book here
        if(!empty($request->image)) {
            // Delete old image
            File::delete(public_path('uploads/books/'. $book->image));
            File::delete(public_path('uploads/books/thumb/'. $book->image));

            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->move(public_path('uploads/books'), $imageName);
            
            $book->image = $imageName;
            $book->save();

            $manager = new ImageManager(Driver::class);
            $img = $manager->read(public_path('uploads/books/'. $imageName));
            $img->resize(990);
            $img->save(public_path('uploads/books/thumb/'.$imageName));
        }

        return redirect()->route('books.index')->with('success', 'Book updated successfully.');
    }
    
    // This method will delete a book from database
    public function destroy(Request $request) {
        $book = Book::find($request->id);

        if($book == null) {
            session()->flash('error', 'Book not found');
            return response()->json([
                'status' => false,
                'message' => 'Book not found'
            ]);
        } else {
            // Delete book image
            File::delete(public_path('uploads/books/'. $book->image));
            File::delete(public_path('uploads/books/thumb/'. $book->image));
            $book->delete();

            session()->flash('success', 'Book deleted successfully.');
            return response()->json([
                'status' => true,
                'message' => 'Book deleted successfully.'
            ]);

        }
    }
}
