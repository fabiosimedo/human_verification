<div
id="linkModal"
class="fixed inset-0 bg-black/50 hidden flex items-center justify-center">

<div class="bg-white rounded-2xl p-6 w-full max-w-md">

<h2 class="text-lg font-semibold">
Editar link
</h2>

<form
id="linkForm"
method="POST"
class="mt-4 space-y-4">

@csrf

<div>
<label class="text-sm font-medium">
Nome do link
</label>

<input
id="linkTitle"
name="title"
type="text"
class="mt-1 w-full border rounded-lg p-2"
required
>
</div>

<div>
<label class="text-sm font-medium">
URL
</label>

<input
id="linkUrl"
name="url"
type="url"
class="mt-1 w-full border rounded-lg p-2"
required
>
</div>

<div class="flex justify-between">

<button
type="submit"
class="bg-black text-white px-4 py-2 rounded-lg">
Salvar
</button>

<button
type="button"
onclick="closeModal()"
class="text-gray-500">
Cancelar
</button>

</div>

</form>

</div>

</div>
