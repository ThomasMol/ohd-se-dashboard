<h3>Contributie</h3>
<label for="datum">Datum</label>
<input type="date" class="form-control mb-3" id="datum" name="datum" value="{{ old('datum') ?? $contributie->datum}}" required>
<label for="bedrag">Bedrag</label>
<input type="number" class="form-control mb-3" id="budget" name="bedrag" step=".01" min="0" value="{{ old('bedrag') ?? $contributie->bedrag}}" required>

<label for="contributie_soort">Contributie soort</label>
<select class="form-control mb-3" id="contributie_soort" name="contributie_soort" required>
    <option selected value="Maandcontributie">Maandcontributie</option>
    <option value="Half jaars contributie">Half jaars contributie</option>
    <option value="Kerstdiner">Kerstdiner</option>
    <option value="1e Weekend">1e Weekend</option>
    <option value="2e Wekend">2e Weekend</option>
    <option value="Overig">Overig</option>
</select>

<h3>Aanwezigheid</h3>
<table class="table table-hover table-sm">
    <thead>
    <tr>
        <th scope="col">Naam</th>
        <th scope="col">Aanwezig</th>
    </tr>
    </thead>
    <tbody>
    @foreach($leden as $lid)
        <tr>
            <th scope="row">{{ $lid->roepnaam }} {{ $lid->achternaam }}</th>
            <td><input type="checkbox" name="deelnemers[]" value="{{$lid->lid_id}}" @if(isset($lid->deelname)) checked @endif></td>
    @endforeach

    </tbody>
</table>