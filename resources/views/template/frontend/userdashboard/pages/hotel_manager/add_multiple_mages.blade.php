
@extends('hotel_manager.manager_master')

@section('content')


<div class="dashboard-content">
    <form action='{{ route("projects.store") }}' method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Name/Description fields, irrelevant for this article --}}

        <div class="form-group">
            <label for="document">Documents</label>
            <div class="needsclick dropzone" id="document-dropzone">

            </div>
        </div>
        <div>
            <input class="btn btn-danger" id="submit" type="button">
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script>
  

  $('#submit').click(function(){
      alert('working');
  });
  var uploadedDocumentMap = {}
  Dropzone.options.documentDropzone = {
    url: '{{ route('projects.storeMedia') }}',
    maxFilesize: 2, // MB
    addRemoveLinks: true,
    data:{'id':8},
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },


    success: function (file1, response,id) {
        console.log(response);
        $('form').append('<input type="hidden" name="document[]" value="' + response.name + '"><input type="hidden" name="id[]" value="' + response.id + '">')
        uploadedDocumentMap[file1.name] = response.name
        // uploadedDocumentMap[id.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedDocumentMap[file.name]
      }
      $('form').find('input[name="document[]"][value="' + name + '"]').remove()
    },
    init: function () {
      @if(isset($project) && $project->document)
        var files =
          {!! json_encode($project->document) !!}
        for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
        }
      @endif
    }
  }
</script>
@stop



