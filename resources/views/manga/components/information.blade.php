<ul class="list-group">
    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-tags"></span>&nbsp;
                <b>Genres</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->genreReferences))
                    <div class="row">
                        @foreach ($manga->genreReferences as $genreReference)
                            @php
                                $genre = $genreReference->genre;
                            @endphp
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                                <a href="{{ \URL::action('GenreController@index', [$genre->name]) }}">
                                    {{ $genre->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    Unable to find genres.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-list"></span>&nbsp;
                <b>Names</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->associatedNameReferences))
                    <div class="row">
                        @foreach ($manga->associatedNameReferences as $associatedNameReference)
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                                {{ $associatedNameReference->associatedName->name }}
                            </div>
                        @endforeach
                    </div>
                @else
                    Unable to find associated names.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-pencil"></span>&nbsp;
                <b>Authors</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->authorReferences))
                    <div class="row">
                        @foreach ($manga->authorReferences as $authorReference)
                            @php
                                $author = $authorReference->author;
                            @endphp
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                                <a href="{{ \URL::action('PersonController@index', [$author]) }}">
                                    {{ $author->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    Unable to find authors.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-brush"></span>&nbsp;
                <b>Artists</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->artistReferences))
                    <div class="row">
                        @foreach ($manga->artistReferences as $artistReference)
                            @php
                                $artist = $artistReference->artist;
                            @endphp
                            <div class="col-6 col-sm-4 col-md-3 col-lg-3">
                                <a href="{{ \URL::action('PersonController@index', [$artist]) }}">
                                    {{ $artist->name }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    Unable to find artists.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-info"></span>&nbsp;
                <b>Summary</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->description))
                    {!! nl2br(e($manga->description)) !!}
                @else
                    Unable to find description.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-calendar"></span>&nbsp;
                <b>Year</b>
            </div>
            <div class="col-9 col-md-10">
                @if (! empty($manga->year))
                    {{ $manga->year }}
                @else
                    Unable to find year.
                @endif
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2 list-group-item-prepend">
                <span class="fa fa-star"></span>
                <b>Rating</b>
            </div>
            <div class="col-9 col-md-10">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <strong>Average</strong>
                        @if ($manga->votes->count() > 0)
                            @php
                                $averageRating = \App\Rating::average($manga);
                                if ($averageRating !== false)
                                    $averageRating = round($averageRating);

                                $userVote = $user->votes->where('manga_id', $manga->id)->first();
                            @endphp

                            <p>
                                {{ $averageRating }}
                                @if (! empty($userVote))
                                    <span class="fa fa-check text-success" title="You've voted!"></span>
                                @endif
                            </p>
                        @else
                            <p>N/A</p>
                        @endif
                    </div>
                    <div class="col-6 col-sm-4">
                        <strong title="Lower bound Wilson score">Wilson&nbsp;<a href="https://www.evanmiller.org/how-not-to-sort-by-average-rating.html">?</a></strong>
                        @if ($manga->votes->count() > 0)
                            @php
                                $rating = \App\Rating::get($manga);
                                if ($rating !== false)
                                    $rating = round($rating, 2);
                            @endphp
                            <p title="Lower bound Wilson score">{{$rating }}</p>
                        @else
                            <p title="Lower bound Wilson score">N/A</p>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <strong>Vote</strong>

                        <div class="row">
                            <div class="col-12">
                                @if (empty($userVote))
                                    {{ Form::open(['action' => 'VoteController@put', 'method' => 'put', 'style' => 'display:inline-block;']) }}
                                    {{ Form::hidden('manga_id', $manga->id) }}
                                @else
                                    {{ Form::open(['action' => 'VoteController@patch', 'method' => 'patch', 'style' => 'display:inline-block;']) }}
                                    {{ Form::hidden('vote_id', $userVote->id) }}
                                @endif
                                    <div class="input-group">
                                        <select class="custom-select" name="rating">
                                            @for ($i = 100; $i >= 0; $i--)
                                                <option value="{{ $i }}"
                                                    @if (! empty($userVote) && ($userVote->rating === $i))
                                                        selected
                                                    @elseif ($i === 70)
                                                        selected
                                                    @endif
                                                >
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-sm btn-success" type="submit">
                                                <span class="fa fa-check"></span>
                                            </button>
                                        </div>
                                    </div>
                                {{ Form::close() }}

                                @if (! empty($userVote))
                                    {{ Form::open(['action' => 'VoteController@delete', 'method' => 'delete', 'style' => 'display:inline-block']) }}
                                    {{ Form::hidden('vote_id', $userVote->id) }}
                                    <button type="submit" class="btn btn-sm btn-danger"><span class="fa fa-times"></span>&#8203;</button>
                                    {{ Form::close() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>

    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-share"></span>
                <b>Actions</b>
            </div>
            <div class="col-9 col-md-10">
                <div class="row">
                    @php
                        $favorite = $user->favorites->where('manga_id', $manga->id)->first();
                        $watchReference = $user->watchReferences->where('manga_id', $manga->id)->first();

                        $isFavorited = ! empty($favorite);
                        $isWatching = ! empty($watchReference);
                    @endphp
                    <div class="col-6 col-sm-4 col-md-3">
                        @if ($isFavorited == false)
                            {{ Form::open(['action' => 'FavoriteController@create']) }}
                            {{ Form::hidden('manga_id', $manga->id) }}
                            <button class="btn btn-success" type="submit">
                                <span class="fa fa-heart"></span>&nbsp;Favorite
                            </button>
                            {{ Form::close() }}
                        @else
                            {{ Form::open(['action' => 'FavoriteController@delete', 'method' => 'delete']) }}
                            {{ Form::hidden('favorite_id', $favorite->id) }}
                            <button class="btn btn-danger" type="submit">
                                <span class="fa fa-remove"></span>&nbsp;Unfavorite
                            </button>
                            {{ Form::close() }}
                        @endif
                    </div>

                    <div class="col-6 col-sm-4 col-md-3">
                        {{ Form::open(['action' => 'WatchController@update']) }}

                        {{ Form::hidden('id', $manga->id) }}

                        @if ($isWatching == false)
                            {{ Form::hidden('action', 'watch') }}
                            <button class="btn btn-success" type="submit" title="Get notifications for new archives">
                                <span class="fa fa-eye"></span>&nbsp;Watch
                            </button>
                        @else
                            {{ Form::hidden('action', 'unwatch') }}
                            <button class="btn btn-danger" type="submit" title="Do not get notifications for new archives">
                                <span class="fa fa-times"></span>&nbsp;Unwatch
                            </button>
                        @endif

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </li>

    @admin
    <li class="list-group-item">
        <div class="row">
            <div class="col-3 col-md-2">
                <span class="fa fa-hdd"></span>&nbsp;
                <b>Path</b>
            </div>
            <div class="col-9 col-md-10">
                {{ $manga->path }}
            </div>
        </div>
    </li>
    @endadmin
</ul>
