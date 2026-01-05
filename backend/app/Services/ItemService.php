<?php
namespace App\Services;

use App\Models\Item;

class ItemService implements ItemServiceInterface
{
    public function createItem(array $data): Item
    {
        if (isset($data['image'])) {
            $data['image_path'] = $this->storeImage($data['image']);
        }

        return Item::create([
            'name' => $data['name'],  
            'price' => $data['price'],  
            'description' => $data['description'], 
            'image_path' => $data['image_path'] ?? null,  
        ]);
    }

    public function getAllItems()
    {
        return Item::all();
    }

    public function findItem(int $id): Item
    {
        return Item::findOrFail($id);
    }

    public function updateItem(int $id, array $data): Item
    {
        $item = Item::findOrFail($id);

        if (isset($data['image'])) {
            $data['image_path'] = $this->storeImage($data['image']);
        }

        $item->update([
            'name' => $data['name'],  
            'price' => $data['price'],  
            'description' => $data['description'],  
            'image_path' => $data['image_path'] ?? $item->image_path,  
        ]);

        return $item;
    }

    public function deleteItem(int $id): void
    {
        $item = Item::findOrFail($id);
        $item->delete();
    }

    private function storeImage($image)
    {
        return $image->store('images/items', 'public');
    }
}

