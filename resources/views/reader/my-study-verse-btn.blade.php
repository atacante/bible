@if($model->verse)
    <div class="modal-header-btn study-verse-full">
        <a href="{!! url('/reader/my-study-verse?version='.$model->bible_version.'&verse_id='.$model->verse->id) !!}" data-type="note" class="btn4-kit s-full-screen-btn j-full-screen-btn">
            <i class="bs-study"></i>
            My Study Verse
        </a>
    </div>
@endif