import { useState, useEffect } from 'react'
import type { Library } from './types'

const platforms: string[] = ['all', 'Switch', 'PS5', 'Xbox Series', 'PC'] //bad... TODO later add a table of all type in DB maybe
const QUERY = `{
  library(id: 1) {
    title
    owned_games { id comment game { title platform } }
  }
}`

function App() {
	const [library, setLibrary] = useState<Library | null>(null)
	const [platform, setPlatform] = useState<string>('all')
	const [error, setError] = useState<string | null>(null)

	useEffect(() => {
		const controller = new AbortController()

		fetch('/graphql', {
			method: 'POST',
			headers: { 'Content-Type': 'application/json' },
			body: JSON.stringify({ query: QUERY }),
			signal: controller.signal,
		})
		.then((response) => response.json())
		.then((data) => {
			if (data.errors) {
				throw new Error(data.errors[0].message)
			}
			setLibrary(data.data.library)
		})
		.catch((err) => {
			if (err.name !== 'AbortError') {
				setError(err.message)
			}
		})
		return () => controller.abort()
	}, [])

	if (error) return <p>Erreur : {error}</p>
	if (!library) return <p>Chargement…</p>

	const displayedGames = platform === 'all' ?
		library.owned_games :
		library.owned_games.filter((ownedGame) => ownedGame.game.platform === platform)

	return (
		<>
			<h1>{library.title}</h1>

			<select
				value={platform}
				onChange={(e) => {
					return setPlatform(e.target.value)
				}}
			>
				{platforms.map((platformOption) => {
					return <option key={platformOption} value={platformOption}>
						{platformOption}
					</option>
				})}
			</select>
			<ul>
				{displayedGames.map((ownedGame) =>  {
					return <li key={ownedGame.id}>
						<p>{ownedGame.game.title} ({ownedGame.game.platform})</p>
					</li>
				})}
			</ul>
		</>
	)
}

export default App
