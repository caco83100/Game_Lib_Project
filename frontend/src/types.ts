export type Game = {
    title: string;
    platform: string
}

export type OwnedGame = {
    id: string;
    comment: string | null;
    game: Game
}

export type Library = {
    title: string;
    owned_games: OwnedGame[]
}
