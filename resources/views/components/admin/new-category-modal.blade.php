{{-- Category quick-add: the description column exists on categories, so this
     dialog offers it. Rendered inside the post editor's taxonomy() scope. --}}
<x-admin.quick-add-term-modal kind="category" title="New category" :description="true" />
