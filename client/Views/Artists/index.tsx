import React, { useEffect, useState } from 'react';
import { Artist, getArtists } from '../../logic/Artist';
import { User } from '../../logic/User';
import AmpacheError from '../../logic/AmpacheError';
import ArtistDisplay from '../components/ArtistDisplay';
import ReactLoading from 'react-loading';
import { toast } from 'react-toastify';

interface ArtistsViewProps {
    user: User;
}

const ArtistsView: React.FC<ArtistsViewProps> = (props: ArtistsViewProps) => {
    const [artists, setArtists] = useState<Artist[]>(null);
    const [error, setError] = useState<Error | AmpacheError>(null);

    useEffect(() => {
        getArtists(props.user.authKey)
            .then((data) => {
                setArtists(data);
            })
            .catch((error) => {
                toast.error('😞 Something went wrong getting the artist.');
                setError(error);
            });
    }, [props.user.authKey]);

    if (error) {
        return (
            <div className='artistsPage'>
                <span>Error: {error.message}</span>
            </div>
        );
    }
    if (!artists) {
        return (
            <div className='artistsPage'>
                <ReactLoading color='#FF9D00' type={'bubbles'} />
            </div>
        );
    }
    return (
        <div className='artistsPage'>
            <div className='details'>
                {/*<div className='imageContainer'>*/}
                {/*    <img*/}
                {/*        src={this.state.theArtist.art}*/}
                {/*        alt={'Album Cover'}*/}
                {/*    />*/}
                {/*</div>*/}
                {/*Name: {this.state.theArtist.name}*/}
            </div>
            <h1>Artists</h1>
            <div className='artists'>
                {artists.map((theArtist) => {
                    return (
                        <ArtistDisplay artist={theArtist} key={theArtist.id} />
                    );
                })}
            </div>
        </div>
    );
};

export default ArtistsView;
