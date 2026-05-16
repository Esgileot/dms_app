import { PageProps as InertiaPageProps } from '@inertiajs/core'

export interface User {
    id: number
    name: string
    email: string
    email_verified_at: string | null
}

export interface PageProps extends InertiaPageProps {
    auth: {
        user: User
    }
}