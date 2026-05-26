@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 8rem; padding-bottom: 5rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h2 class="section-title" style="margin: 0;">Admin Dashboard</h2>
        <a href="{{ route('admin.logout') }}" style="padding: 0.5rem 1rem; background: #666; color: white; border: none; border-radius: 4px; cursor: pointer; text-decoration: none; font-size: 0.9rem;">Logout</a>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 1rem; border-radius: 4px; margin-bottom: 2rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 2rem;">
        <!-- Add Menu Item Form -->
        <div style="background: white; padding: 2rem; border-radius: 8px; border: 1px solid var(--gold); height: fit-content;">
            <h3 style="margin-bottom: 1.5rem; color: var(--crimson);">Add New Menu Item</h3>
            <form action="{{ route('admin.menu.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Name</label>
                    <input type="text" name="name" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Description</label>
                    <textarea name="description" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px; height: 100px;"></textarea>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Price (Rp)</label>
                    <input type="number" name="price" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Category</label>
                    <select name="category_id" required style="width: 100%; padding: 0.5rem; border: 1px solid #ddd; border-radius: 4px;">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Image</label>
                    <input type="file" name="image" required style="width: 100%;">
                </div>
                <button type="submit" style="width: 100%; padding: 1rem; background: var(--crimson); color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Add Item
                </button>
            </form>
        </div>

        <!-- Menu Items List -->
        <div style="background: white; padding: 2rem; border-radius: 8px; border: 1px solid #eee;">
            <h3 style="margin-bottom: 1.5rem;">Current Menu Items</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 1rem;">Item</th>
                        <th style="padding: 1rem;">Category</th>
                        <th style="padding: 1rem;">Price (Rp)</th>
                        <th style="padding: 1rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($menuItems as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 1rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <img src="{{ asset($item->image) }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                <span>{{ $item->name }}</span>
                            </div>
                        </td>
                        <td style="padding: 1rem;">{{ $item->category->name }}</td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.menu.update', $item->id) }}" method="POST" style="display: flex; gap: 0.5rem;">
                                @csrf
                                @method('PUT')
                                <input type="number" name="price" value="{{ $item->price }}" style="width: 100px; padding: 0.3rem; border: 1px solid #ddd; border-radius: 4px;">
                                <button type="submit" style="padding: 0.3rem 0.5rem; background: var(--gold); color: white; border: none; border-radius: 4px; cursor: pointer;">Update</button>
                            </form>
                        </td>
                        <td style="padding: 1rem;">
                            <form action="{{ route('admin.menu.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="padding: 0.5rem; background: #dc3545; color: white; border: none; border-radius: 4px; cursor: pointer;">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
