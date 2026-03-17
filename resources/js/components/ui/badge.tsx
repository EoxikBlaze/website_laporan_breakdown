import * as React from "react";
import { Slot } from "@radix-ui/react-slot";
import { cva, type VariantProps } from "class-variance-authority";
import { cn } from "@/lib/utils";

export const badgeVariants = cva(
  "relative inline-flex shrink-0 items-center justify-center gap-1 whitespace-nowrap rounded-sm border border-transparent font-medium outline-none transition-shadow focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-1 focus-visible:ring-offset-background disabled:pointer-events-none disabled:opacity-60 [&_svg]:pointer-events-none [&_svg]:shrink-0 [&_svg]:size-3.5",
  {
    defaultVariants: {
      size: "default",
      variant: "default",
    },
    variants: {
      size: {
        default: "h-[22px] min-w-[22px] px-[3px] text-xs",
        lg:      "h-[26px] min-w-[26px] px-[5px] text-sm",
        sm:      "h-[18px] min-w-[18px] rounded px-[3px] text-[10px]",
      },
      variant: {
        default:
          "bg-primary text-primary-foreground hover:bg-primary/90",
        destructive:
          "bg-destructive text-white hover:bg-destructive/90",
        success:
          "bg-emerald-100/80 text-emerald-700 border-emerald-200",
        warning:
          "bg-yellow-100/80 text-yellow-700 border-yellow-200",
        danger:
          "bg-rose-100/80 text-rose-700 border-rose-200",
        info:
          "bg-sky-100/80 text-sky-700 border-sky-200",
        outline: "text-foreground border-border",
        secondary:
          "bg-secondary text-secondary-foreground hover:bg-secondary/80",
      },
    },
  },
);

export interface BadgeProps extends React.ComponentPropsWithoutRef<"span"> {
  variant?: VariantProps<typeof badgeVariants>["variant"];
  size?: VariantProps<typeof badgeVariants>["size"];
  render?: React.ReactElement;
  asChild?: boolean;
}

export function Badge({
  className,
  variant,
  size,
  render,
  asChild = false,
  ...props
}: BadgeProps): React.ReactElement {
  const badgeClass = cn(badgeVariants({ variant, size }), className);

  if (render) {
    return React.cloneElement(render, {
      ...props,
      className: cn(badgeClass, (render.props as React.HTMLAttributes<HTMLElement>)?.className),
      "data-slot": "badge",
    } as React.HTMLAttributes<HTMLElement>);
  }

  const Comp = asChild ? Slot : "span";
  return <Comp className={badgeClass} data-slot="badge" {...props} />;
}
