import render from '../../views/components/separator.twig'

export default {
    title: 'Layout/Separator',
    render: ({ ...args }) => {
        return render(args)
    },
};

export const Big = {
    args: {
        variants: ['big']
    },
};

export const Medium = {
    args: {
        variants: ['medium']
    },
};

export const Small = {
    args: {
        variants: ['small']
    },
};
