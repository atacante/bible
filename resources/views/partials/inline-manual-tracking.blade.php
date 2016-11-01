@if(Auth::check())
<script>
    window.inlineManualTracking = {
        uid: "{!! Auth::user()->id !!}", // Only this field is mandatory
        name: "{!! Auth::user()->name !!}",
        created: "{!! strtotime(Auth::user()->created_at) !!}",
        roles: ["beta", "free"],
    }
</script>
@endif