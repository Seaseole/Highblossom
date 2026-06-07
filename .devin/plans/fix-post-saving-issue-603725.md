# Fix: Post Editor Saving Issue

Edits to blog posts were not being persisted to the database because the `UpdatePost` action was expecting fields that didn't match the names used in the Livewire component, and the `savePost` method in the component was passing null for `seo_metadata`.

## Proposed Changes

### 1. Fix `UpdatePost` Action
The `UpdatePost` action currently uses a strict mapping that doesn't account for missing keys correctly. I will update it to be more flexible and ensure it uses the provided data if present.

### 2. Update `⚡editor.blade.php` Component
- Correct the mapping in `savePost()` to match what `UpdatePost` expects.
- Specifically fix `categoryId` (component) vs `category_id` (action).
- Fix the `seo_metadata` issue where it might be passed as null from the model if not yet initialized.
- Ensure `isPublished` and `isFeatured` are also included in the save operation.

### 3. Verify `UpdateBlock` Action
- I've already checked `UpdateBlock`, and it seems to correctly use `validator($data, $blockType::validationRules())->validate()`. I will double-check that the component passes the correct data structure to it.

## Questions for the User
1. Is it only the main post fields (Title, Slug, Category, etc.) that aren't saving, or are the content blocks also not saving?
2. Are you seeing any validation error toasts when you click "Save"?
